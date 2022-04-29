<?php

namespace SqlBuilder;

/**
 * SQL join
 */
interface IJoin
{
    /**
     * Make SQL join string
     */
    public function __toString();
}
