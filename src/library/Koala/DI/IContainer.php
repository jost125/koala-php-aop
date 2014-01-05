<?php

namespace Koala\DI;

use Koala\DI\Extension\IBeforeCompileExtension;

interface IContainer {
	public function registerBeforeCompileExtension(IBeforeCompileExtension $extension);
	public function getService($serviceId);
	public function getParameter($parameterId);
	public function compile();
}
