<?php

namespace DI;

interface IContainer {
	public function getService($serviceId);
	public function getParameter($parameterId);
}
