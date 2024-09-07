<?php

namespace organizationManagement\domain\model\common\exception;

use RuntimeException;

/**
 * メソッドが不正な状態で呼び出されたときに使う
 */
class IllegalStateException extends RuntimeException {}