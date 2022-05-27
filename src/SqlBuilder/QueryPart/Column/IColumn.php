<?php

namespace SqlBuilder\QueryPart\Column;

interface IColumn
{
    /**
     * Make SQL column string
     */
    public function __toString();

}
