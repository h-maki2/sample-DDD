<?php

namespace organizationManagement\application\common;

interface JsonSerializable
{
    public function jsonSerialize(): array;
}