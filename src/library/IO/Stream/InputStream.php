<?php

namespace IO\Stream;

interface InputStream {

	const EOF = -1;

	public function peek();
	public function read();
	public function pointer();
	public function readSection($start, $end);
	public function startRecording();
	public function stopRecording();
	public function getRecord();

}
