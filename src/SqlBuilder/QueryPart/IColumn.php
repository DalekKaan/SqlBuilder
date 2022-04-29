<?php

namespace SqlBuilder\QueryPart;

interface IColumn
{
    /**
     * Make SQL column string
     */
    public function __toString();

}
