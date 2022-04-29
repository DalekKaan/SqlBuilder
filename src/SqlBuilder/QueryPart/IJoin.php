<?php

namespace SqlBuilder\QueryPart;

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
