<?php
namespace Soukicz;

abstract class RuntimeException extends \RuntimeException {

}

abstract class LogicException extends \LogicException {

}

class ArgumentException extends LogicException {

}

class InputException extends RuntimeException {

}

class IOException extends RuntimeException {

}
