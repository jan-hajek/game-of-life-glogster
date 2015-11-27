<?php
namespace App;

class XmlProviderException extends \Exception
{
	const READ_FILE_FAIL = 1;
	const PARSE_ERROR = 2;
	const WRONG_FORMAT = 3;
	const WRITE_FILE_FAIL = 4;
}