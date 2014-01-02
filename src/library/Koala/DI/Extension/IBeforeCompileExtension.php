<?php

namespace Koala\DI\Extension;

use Koala\DI\Definition\Configuration\ConfigurationDefinition;

interface IBeforeCompileExtension {

	public function load(ConfigurationDefinition $configurationDefinition);

}
