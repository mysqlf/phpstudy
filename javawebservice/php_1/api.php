<?php
    class URLUtils {
        public static function simpleEncode($s) {
            $s = (string) $s;
            $sb = null;
            for ($i = 0; $i < strlen($s); $i++) {
                $c0 = $s[$i];
                if ($c0 <= ' ' || $c0 == '?' || $c0 == '%' || $c0 == '=' || $c0 == '&' || $c0 == '#') {
                    if ($sb == null) {
                        $sb = substr($s, 0, $i);
                    }
                    $xx = ord($c0);
                    $sb = $sb  . '%' . ($xx < 0x10 ? '0' : '') . dechex($xx);
                } else {
                    if ($sb != null) {
                        $sb = $sb . $c0;
                    }
                }
            }
            return ($sb != null) ? $sb : $s;
        }
    }

    function ary2String( $ary ) {
        if (!is_array($ary)) {
            return $ary;
        }

        $s = "";
        foreach ($ary as $key => $val) {
            if ( $s != "" )
                $s .= "&";
            $s .= URLUtils::simpleEncode($key) . "=" . URLUtils::simpleEncode($val);
        }
        return $s;
    }

    function string2Ary( $s ) {
        parse_str( $s, $res );
        return $res;
    }


    class CoremailArray {
        private $items;

        function CoremailArray($items) {
            switch (func_num_args()) {
            case 0:
                $this->items = [];
                break;
            case 1:
                $this->items = self::isSequentialArray($items) ? $items : [$items];
                break;
            default:
                $this->items = func_get_args();
            }
        }

        function getItems() {
            return $this->items;
        }

        private static function isSequentialArray($arr) {
            return is_array($arr) && (array_keys($arr) === range(0, count($arr) - 1));
        }
    }


    class CoremailAPI {
        function CoremailAPI() {
            $this->socket = null;
        }

        function getErrorCode() { return $this->errCode; }
        function getErrorString() { return $this->errString; }
        function getResult() { return $this->result; }

        function close() {
            if ( $this->socket != null ) {
                fclose($this->socket);
                $this->socket = null;
            }
            $this->errCode = 0;
            $this->errString = "";
            $this->result = "";
        }

        function open( $host, $port = 6195, $timeout = 10 ) {
            if ($this->socket) {
                $this->close();
            }
            if (!($this->socket = fsockopen( $host, $port, $this->errCode, $this->errString, $timeout))) {
                $this->socket = null;
                return $this->errReport(2, "connect failed");
            }
            stream_set_timeout($this->socket, $timeout);
            return true;
        }

        function setTimeout( $timeout ) {
            stream_set_timeout( $this->socket, $timeout );
        }

        function call( $strArgs, $strAttrs) {
            if ( is_array($strArgs) )
                $ar = $strArgs;
            else {
                parse_str( $strArgs, $ar );
                foreach ($ar as $key => $val) {
                    if ( in_array( $key, self::$aryInt ) )
                        $ar[ $key ] = intval($val);
                }
            }
            if ( !is_null($strAttrs) )
                $ar["attrs"] = ary2String($strAttrs);
            return $this->cmd( $ar );
        }

        /////////////////////////////////////////////////////////////////////////////////

        function	addUser( $strOrgID, $strUserAtDomain, $strParams) { return $this->cmd( array(
            "cmd" => 0,
            "org_id" => $strOrgID,
            "user_at_domain" => $strUserAtDomain ,
            "attrs" => ary2String($strParams)
        )); }

        function	delUser( $strUserAtDomain ) { return $this->cmd( array(
            "cmd" => 2,
            "user_at_domain" => $strUserAtDomain
        )); }

        function	alterUserInfo($strUserAtDomain, $strParams) { return $this->cmd( array(
            "cmd" => 1,
            "user_at_domain" => $strUserAtDomain,
            "attrs" => ary2String($strParams)
        ));	}

        function	getUserInfo($strUserAtDomain, $strParams) { return $this->cmd( array(
            "cmd" => 3,
            "user_at_domain" => $strUserAtDomain,
            "attrs" => ary2String($strParams)
        ));	}

        function	userLogin( $strUserAtDomain, $strParams) { return $this->cmd( array(
            "cmd" => 5,
            "user_at_domain" => $strUserAtDomain ,
            "attrs" => ary2String($strParams)
        ));	}

        function	userLogout( $strSessionID ) { return $this->cmd( array(
            "cmd" => 6,
            "ses_id" => $strSessionID
        ));	}

        function	hasUser( $strUserAtDomain ) { return $this->cmd( array(
            "cmd" => 4,
            "user_at_domain" => $strUserAtDomain
        ));	}

        function	setTempVar( $strSessionID, $strParams ) { return $this->cmd( array(
            "cmd" => 7,
            "ses_id" => $strSessionID,
            "ses_key" => "TempVar",
            "ses_var" => $strParams
        ));	}

        function	getTempVar( $strSessionID ) { return $this->cmd( array(
            "cmd" => 8,
            "ses_id" => $strSessionID,
            "ses_key" => "TempVar"
        ));	}

        function	checkSesTimeout( $strSessionID ) { return $this->cmd( array(
            "cmd" => 9,
            "ses_id" => $strSessionID
        ));	}

        function	checkPass( $strUserAtDomain, $strPass ) { return $this->cmd( array(
            "cmd" => 12,
            "user_at_domain" => $strUserAtDomain ,
            "password" => $strPass
        ));	}

        function	refreshSes(	$strSessionID ) { return $this->cmd( array(
            "cmd" => 10,
            "ses_id" => $strSessionID
        ));	}

        /////////////////////////////////////////////////////////////////////////////////

        function	getExtUserInfo($strUserAtDomain, $strParams) { return $this->cmd( array(
            "cmd" => 37,
            "user_at_domain" => $strUserAtDomain,
            "attrs" => ary2String($strParams)
        ));	}

        function	getAdminType($strUserAtDomain) { return $this->cmd( array(
            "cmd" => 36,
            "user_at_domain" => $strUserAtDomain
        ));	}

        function	getSessionVar( $strSessionID, $strKey ) { return $this->cmd( array(
            "cmd" => 8,
            "ses_id" => $strSessionID,
            "ses_key" => $strKey
        ));	}

        function	addDomain($strDomain) { return $this->cmd( array(
            "cmd" => 31,
            "domain_name" => $strDomain
        ));	}

        function	addDomainAlias($strDomain, $strDomainAlias) { return $this->cmd( array(
            "cmd" => 32,
            "domain_name" => $strDomain,
            "domain_name_alias" => $strDomainAlias
        ));	}

        function	listDomainAlias($strDomain) { return $this->cmd( array(
            "cmd" => 34,
            "domain_name" => $strDomain
        ));	}

        function	delDomainAlias($strDomain, $strDomainAlias) { return $this->cmd( array(
            "cmd" => 33,
            "domain_name" => $strDomain,
            "domain_name_alias" => $strDomainAlias
        ));	}

        function	delDomain($strDomain) { return $this->cmd( array(
            "cmd" => 35,
            "domain_name" => $strDomain
        ));	}

        function	domainExist($strDomain) { return $this->cmd( array(
            "cmd" => 49,
            "domain_name" => $strDomain
        ));	}

        function	listDomain() { return $this->cmd( array(
            "cmd" => 51
        ));	}

        function	getOrgByDomain($strDomain) { return $this->cmd( array(
            "cmd" => 50,
            "domain_name" => $strDomain
        ));	}

        function	addFilter($strUserAtDomain, $strFilter) { return $this->cmd( array(
            "cmd" => 18,
            "user_at_domain" => $strUserAtDomain,
            "attrs" => ary2String($strFilter)
        ));	}

        function	removeFilter($strUserAtDomain, $strFilterName) { return $this->cmd( array(
            "cmd" => 19,
            "user_at_domain" => $strUserAtDomain,
            "attrs" => ary2String($strFilterName)
        ));	}

        function	addOrg($strOrgID, $strParams) { return $this->cmd( array(
            "cmd" => 22,
            "org_id" => $strOrgID,
            "attrs" => ary2String($strParams)
        ));	}

        function	addOrgCos($strOrgID, $nCosID, $nNumOfClass) { return $this->cmd( array(
            "cmd" => 28,
            "org_id" => $strOrgID,
            "cos_id" => $nCosID,
            "num_of_classes" => $nNumOfClass
        ));	}

        function	alterOrgCos($strOrgID, $nCosID, $nNumOfClass) { return $this->cmd( array(
            "cmd" => 29,
            "org_id" => $strOrgID,
            "cos_id" => $nCosID,
            "num_of_classes" => $nNumOfClass
        ));	}

        function	delOrgCos($strOrgID, $nCosID ) { return $this->cmd( array(
            "cmd" => 30,
            "org_id" => $strOrgID,
            "cos_id" => $nCosID
        ));	}

        function	getOrgInfo($strOrgID, $strParams ) { return $this->cmd( array(
            "cmd" => 24,
            "org_id" => $strOrgID,
            "attrs" => ary2String($strParams)
        ));	}

        function	alterOrg($strOrgID, $strParams) { return $this->cmd( array(
            "cmd" => 23,
            "org_id" => $strOrgID,
            "attrs" => ary2String($strParams)
        ));	}

        function	listOrgCosUser($strOrgID, $nCosID) { return $this->cmd( array(
            "cmd" => 42,
            "org_id" => $strOrgID,
            "cos_id" => $nCosID
        ));	}

        function	getOrgCosUserMax($strOrgID, $nCosID) { return $this->cmd( array(
            "cmd" => 44,
            "org_id" => $strOrgID,
            "cos_id" => $nCosID
        ));	}

        function	listOrg() { return $this->cmd( array(
            "cmd" => 43
        ));	}

        function	addAllowedDomain($strOrgID, $strDomain) { return $this->cmd( array(
            "cmd" => 26,
            "org_id" => $strOrgID,
            "domain_name" => $strDomain
        ));	}

        function	delAllowedDomain($strOrgID, $strDomain) { return $this->cmd( array(
            "cmd" => 27,
            "org_id" => $strOrgID,
            "domain_name" => $strDomain
        ));	}

        function	addSmtpAlias($strUserAtDomain, $strUserAtDomainAlias) { return $this->cmd( array(
            "cmd" => 45,
            "user_at_domain" => $strUserAtDomain,
            "alias_user_at_domain" => $strUserAtDomainAlias
        ));	}

        function	delSmtpAlias($strUserAtDomain, $strUserAtDomainAlias) { return $this->cmd( array(
            "cmd" => 46,
            "user_at_domain" => $strUserAtDomain,
            "alias_user_at_domain" => $strUserAtDomainAlias
        ));	}

        function	listSmtpAlias($strUserAtDomain) { return $this->cmd( array(
            "cmd" => 47,
            "user_at_domain" => $strUserAtDomain
        ));	}

        function	setFolderByName($strUserAtDomain, $folder, $strParams) { return $this->cmd( array(
            "cmd" => 63,
            "user_at_domain" => $strUserAtDomain,
            "name" => $folder,
            "attrs" => ary2String($strParams)
        ));	}

        function	setFolderByFolderID($strUserAtDomain, $folderID, $strParams) { return $this->cmd( array(
            "cmd" => 63,
            "user_at_domain" => $strUserAtDomain,
            "fid" => $folderID,
            "attrs" => ary2String($strParams)
        ));	}

        function	delFolderByName($strUserAtDomain, $folder ) { return $this->cmd( array(
            "cmd" => 64,
            "user_at_domain" => $strUserAtDomain,
            "name" => $folder
        ));	}

        function	delFolderByFolderID($strUserAtDomain, $folderID ) { return $this->cmd( array(
            "cmd" => 64,
            "user_at_domain" => $strUserAtDomain,
            "fid" => $folderID
        ));	}

        /////////////////////////////////////////////////////////////////////////////////

        function	setAdminType($strUserAtDomain) { return $this->cmd( array(
            "cmd" => 40,
            "user_at_domain" => $strUserAtDomain
        ));	}

        function	checkUserIsProxyState($strUserAtDomain) { return $this->cmd( array(
            "cmd" => 41,
            "user_at_domain" => $strUserAtDomain
        ));	}

        function	getOuName($strOrgID, $strOUID) { return $this->cmd( array(
            "cmd" => 53,
            "org_id" => $strOrgID,
            "org_unit_id" => $strOUID
        ));	}

        function	getOuHiberarchy($strOrgID, $strOUID) { return $this->cmd( array(
            "cmd" => 54,
            "org_id" => $strOrgID,
            "org_unit_id" => $strOUID
        ));	}

        function	getOuHiberarchyByUser($strUserAtDomain) { return $this->cmd( array(
            "cmd" => 55,
            "user_at_domain" => $strUserAtDomain
        ));	}

        function	setSessionVar($strSessionID, $strSesKey, $strSesVal ) { return $this->cmd( array(
            "cmd" => 7,
            "ses_id" => $strSessionID,
            "ses_key" => $strSesKey,
            "ses_var" => $strSesVal
        ));	}

        function	userRename($strUserAtDomain, $strNewName) { return $this->cmd( array(
            "cmd" => 58,
            "user_at_domain" => $strUserAtDomain,
            "new_user_id" => $strNewName
        ));	}

        /////////////////////////////////////////////////////////////////////////////////

        private static function getIfSet(& $var, $def) {
            return isset($var) ? $var : $def;
        }

        function cmd( $cmd ) {
            $this->result = "";
            $this->errCode = 0;
            $this->errString = "";
            $sendCmd = $this->pri_encode( $this->prependCharset($cmd) );

            if (!$this->socket) {
                return $this->errReport(1, "not connect");
            } else if (false == fwrite($this->socket, pack("N", strlen($sendCmd)) . $sendCmd)) {
                return $this->errReport(3, "write error");
            }
            $l = fread($this->socket, 4);
            if (strlen($l) != 4) {
                return $this->errReport(4, "read error: socket closed?");
            }
            $bytes = unpack("N", $l);
            $res = "";
            while (strlen($res) < $bytes[1]) {
                $res1 = fread( $this->socket, $bytes[1] - strlen($res));
                if ($res1) {
                    $res = $res . $res1;
                } else {
                    return $this->errReport(4, "read content error");
                }
            }

            $ary = $this->pri_decode($res);

            $this->errCode = self::getIfSet($ary["retcode"], -1);
            $this->errString  = self::getIfSet($ary["errmsg"], "");
            $this->result = self::getIfSet($ary["result"], "");
            return true;
        }

        private function pri_saveString($s) {
            return pack("N", strlen($s) + 1) . $s . pack('C', 0);
        }

        private function pri_saveArray($var) {
            $res = pack("N", self::pri_countArray($var));
            foreach ($var as $key => $val) {
                $this->pri_saveAttribute($res, $key, $val);
            }
            return $res;
        }

        private static function pri_countArray($var) {
            $count = 0;
            foreach ($var as $key => $val) {
                if ($val instanceof CoremailArray) {
                    $count += count($val->getItems());
                } else {
                    $count++;
                }
            }
            return $count;
        }

        private function pri_saveAttribute(&$res, $key, $val) {
            if (is_int($val)) {
                $res .= $this->pri_saveString($key) . pack("NN", 0x01, $val);
            } else if (is_string($val)) {
                $res .= $this->pri_saveString($key) . pack("N", 0x02) . $this->pri_saveString($val);
            } else if ($val instanceof CoremailArray) {
                foreach ($val->getItems() as $item) {
                    $this->pri_saveAttribute($res, $key, $item);
                }
            } else if (is_array($val)) {
                $res .= $this->pri_saveString($key) . pack("NC", 0x05, 0x01) . $this->pri_saveArray($val);
            }
        }

        function pri_encode($var) {
            return pack("NnnC", 0xffffffff, 0x01, 0x01, 0x01) . $this->pri_saveArray($var);
        }

        private function pri_getString($res, &$off) {
            $l = unpack("N", substr($res, $off) );	$off += 4;
            $s = substr($res, $off, $l[1]  - 1);	$off += $l[1];
            return $s;
        }

        private function pri_getArray( $res, &$off ) {
            $ary = array();
            $ca = unpack("N", substr($res, $off) );	$off += 4;
            $c = $ca[1];
            for ($i = 0; $i < $c; ++$i ) {
                $key = $this->pri_getString( $res, $off );
                $ta = unpack("N", substr($res, $off) ); $off += 4;
                $t = $ta[1];
                if ($t == 0x01) {
                    $ta = unpack("N", substr($res, $off) ); $off += 4;
                    $v = $ta[1];
                } else if ( $t == 0x02 ) {
                    $v = $this->pri_getString( $res, $off);
                } else if ( $t == 0x05 ) {
                    $off += 1;
                    $v = $this->pri_getArray( $res, $off );
                } else if ( $t == 0x06 ) {
                    $taH = unpack("N", substr($res, $off) ); $off += 4;
                    $taL = unpack("N", substr($res, $off) ); $off += 4;
                    if (PHP_INT_SIZE >= 8) { // is64bit
                        $t = ($taH[1] << 32) + $taL[1];
                        $t = $t / 1000000 - 62135769600;
                    } else {           // is32bit: 兼容 32bit 平台, 使用 bc math 计算
                        $t = bcadd(bcmul(sprintf("%u", $taH[1]), "4294967296"), sprintf("%u", $taL[1]));
                        $t = bcsub(bcdiv($t, 1000000), "62135769600");
                    }
                    // 由于 V1 协议不支持标准时间，这里 format 后的实际是服务器端的本地时间而非 UTC 时间
                    $v = gmdate("Y-m-d H:i:s", $t);
                } else if ( $t == 0x03 ) {
                    $v = $this->pri_getString( $res, $off);
                } else {
                    $v = null;
                }

                if (array_key_exists($key, $ary)) {
                    if (is_array($ary[$key]) && array_key_exists(0, $ary[$key])) {
                        $ary[$key][] = $v;
                    } else {
                        $ary[$key] = array($ary[$key], $v);
                    }
                } else {
                    $ary[$key] = $v;
                }
            }
            return $ary;
        }

        function pri_decode($res) {
            $header = unpack("Na/nb/nc", $res);
            if (($header["a"] != 0xffffffff && $header["a"] != -1) || $header["b"] != 0x01 || $header["c"] != 0x01) {
                return $this->errReport(4, "read content error: invalid header");
            }
            $off = 9;
            return 	 $this->pri_getArray($res, $off);
        }


        function reportServerFail() {
            if ($this->errCode != 0) {
                throw new Exception("Server Failed: (" . $this->errCode . ") " . $this->errString);
            }
        }

        private function errReport($code, $message) {
            $this->errCode = $code;
            $this->errString = $message;
            if ($this->reportErrors) {
                throw new Exception($this->errString);
            }
            return false;
        }

        function setReportErrors($b = true) {
            $this->reportErrors = $b ? true : false;
        }

        function setCharset($charset) {
            $this->charset = $charset;
        }

        private function prependCharset(&$cmd) {
            if ($this->charset != null) {
                return array_merge(["__charset" => $this->charset], $cmd);
            }
            return $cmd;
        }

        var $socket;
        var $errCode;
        var $errString;
        var $result;

        private $reportErrors;
        private $charset;

        private static $aryInt = array("cmd", "cos_id", "num_of_classes", "limit", "skip");
    };

