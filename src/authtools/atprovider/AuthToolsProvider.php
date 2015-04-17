<?php

namespace authtools\atprovider;

use SimpleAuth\provider\DataProvider;

interface AuthToolsProvider extends DataProvider{
	public function getAuthTools();
	public function getProviderVersion();
}
