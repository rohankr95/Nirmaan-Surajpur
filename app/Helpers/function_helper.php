<?php
if(!function_exists('echo_active'))
{
    function echo_active($condition)
    {
        return $condition?'active':'';
    }
}
if(!function_exists('echo_selected'))
{
    function echo_selected($condition)
    {
        return $condition?'selected':'';
    }
}

