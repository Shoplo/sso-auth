<?php

namespace SSOAuth;

interface SSOAuthAdapterInterface
{
    public function post($url, $data = [], $headers = []);
}