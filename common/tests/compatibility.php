<?php

/*
 * Ensures compatibility with PHPUnit < 6.x
 */

namespace PHPUnit\Framework\TestCase {
    class_alias('PHPUnit\Framework\TestCase', 'PHPUnit_Framework_TestCase');
}

