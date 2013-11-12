<?php

namespace Koala\Tools;

use Koala\Collection\ArrayList;
use Koala\Collection\IList;

class AutoLoaderCreator {

	public function create($dirToScan, $fileToDump) {
		$classesInFiles = $this->scanFiles($dirToScan)->map(function ($file) use ($fileToDump) {
			$relativePath = $this->getRelativePath($fileToDump, $file);
			return [$this->findClassInFile($file), $relativePath];
		});

		$this->dumpToFile($classesInFiles, $fileToDump);
	}

	private function scanFiles($dirToScan) {
		$dirToScan = rtrim($dirToScan, '/') . '/';
		$dirs = new ArrayList(glob($dirToScan . '*', GLOB_ONLYDIR));
		$files = new ArrayList(glob($dirToScan . '*.php', GLOB_ERR));

		return $files->append($dirs->flatMap(function ($dir) {
			return $this->scanFiles($dir);
		}));
	}

	private function findClassInFile($file) {
		$tokens = token_get_all(file_get_contents($file));
		$ns = '';
		$fqn = '';
		for ($pointer = 0; $pointer < count($tokens); $pointer++) {
			$token = $this->getToken($tokens, $pointer);
			if ($token == 'namespace') {
				$pointer++;
				$ns = $this->readNamespace($tokens, $pointer);
				continue;
			}
			if ($token == 'class' || $token == 'interface') {
				$pointer++;
				$class = $this->readClass($tokens, $pointer);
				$fqn = $ns ? $ns . '\\' . $class : $class;
				break;
			}
		}

		return $fqn;
	}

	private function readNamespace(array $tokens, &$pointer) {
		$this->skipWS($tokens, $pointer);
		$ns = '';
		while ($tokens[$pointer] != ';') {
			$ns .= $this->getToken($tokens, $pointer);
			$pointer++;
		}

		return $ns;
	}

	private function readClass(array $tokens, &$pointer) {
		$this->skipWS($tokens, $pointer);
		$class = '';
		while (!preg_match('~^\s$~', $this->getToken($tokens, $pointer))) {
			$class .= $this->getToken($tokens, $pointer);
			$pointer++;
		}

		return $class;
	}

	private function skipWS(array $tokens, &$pointer) {
		while (preg_match('~^\s$~', $this->getToken($tokens, $pointer)) && $pointer < count($tokens)) {
			$pointer++;
		}
	}

	private function getToken(array $tokens, $pointer) {
		return is_array($tokens[$pointer]) ? $tokens[$pointer][1] : $tokens[$pointer];
	}

	private function getRelativePath($from, $to) {
		// some compatibility fixes for Windows paths
		$from = is_dir($from) ? rtrim($from, '\/') . '/' : $from;
		$to = is_dir($to) ? rtrim($to, '\/') . '/' : $to;
		$from = str_replace('\\', '/', $from);
		$to = str_replace('\\', '/', $to);

		$from = explode('/', $from);
		$to = explode('/', $to);
		$relPath = $to;

		foreach ($from as $depth => $dir) {
			// find first non-matching dir
			if ($dir === $to[$depth]) {
				// ignore this directory
				array_shift($relPath);
			} else {
				// get number of remaining dirs to $from
				$remaining = count($from) - $depth;
				if ($remaining > 1) {
					// add traversals up to first matching dir
					$padLength = (count($relPath) + $remaining - 1) * -1;
					$relPath = array_pad($relPath, $padLength, '..');
					break;
				} else {
					$relPath[0] = './' . $relPath[0];
				}
			}
		}

		return implode('/', $relPath);
	}

	private function dumpToFile(IList $classInFiles, $fileToDump) {
		$classes = '';
		foreach ($classInFiles as list($class, $file)) {
			$classes .= "\t\t'$class' => '$file',\n";
		}

		file_put_contents($fileToDump, "<?php
spl_autoload_register(function(\$className) {
	\$classes = [
" . $classes . "
	];
	if (isset(\$classes[\$className])) {
		include_once __DIR__ . '/' . \$classes[\$className];
	}
});
", LOCK_EX);
	}

}
