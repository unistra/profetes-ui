<?php

namespace Unistra\Profetes\Cache;

interface Cache
{
    public function fetch($id, $ttl = null);

    public function delete($id);

    public function save($id, $value);
}
