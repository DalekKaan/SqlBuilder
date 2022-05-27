<?php

namespace SqlBuilder\QueryPart\Join;

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
