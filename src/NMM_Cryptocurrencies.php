<?php

// Crypto Helper
class NMM_Cryptocurrencies
{
    public static function get()
    {
        // id, name, round_precision, icon_filename, refresh_time, symbol, has_hd, has_autopay, needs_confirmations, erc20contract
        $cryptoArray = array(

            // auto-pay coins
            'ETHO' => new NMM_Cryptocurrency('ETHO', 'Ether-1', 18, 'ether-1_logo.png', 60, 'ETHO', false, true, true, ''),
        );

        return $cryptoArray;
    }

    public static function get_hd()
    {
        $cryptos = self::get();
        $privacyCryptos = [];

        foreach ($cryptos as $crypto) {
            if ($crypto->has_hd()) {
                $privacyCryptos[] = $crypto;
            }
        }

        return $privacyCryptos;
    }

    public static function get_erc20_tokens()
    {
        $cryptos = self::get();
        $erc20Tokens = [];

        foreach ($cryptos as $crypto) {
            if ($crypto->is_erc20_token()) {
                $erc20Tokens[$crypto->get_id()] = $crypto;
            }
        }

        return $erc20Tokens;
    }

    public static function get_non_erc20_tokens()
    {
        $cryptos = self::get();
        $nonErc20Tokens = [];

        foreach ($cryptos as $crypto) {
            if (!$crypto->is_erc20_token()) {
                $nonErc20Tokens[$crypto->get_id()] = $crypto;
            }
        }

        return $nonErc20Tokens;
    }

    public static function is_erc20_token($cryptoId)
    {
        if (array_key_exists($cryptoId, NMM_Cryptocurrencies::get_erc20_tokens())) {
            return true;
        }

        return false;
    }

    public static function get_erc20_contract($cryptoId)
    {
        $erc20Tokens = NMM_Cryptocurrencies::get_erc20_tokens();

        foreach ($erc20Tokens as $token) {
            if ($token->get_id() === $cryptoId) {
                return $token->get_erc20_contract();
            }
        }

        return '';
    }


    public static function get_alpha()
    {
        $cryptoArray = NMM_Cryptocurrencies::get();

        $keys = array_map(function ($val) {
            return $val->get_id();
        }, $cryptoArray);
        array_multisort($keys, $cryptoArray);
        return $cryptoArray;
    }

    // Php likes to convert numbers to scientific notation, so this handles displaying small amounts correctly
    public static function get_price_string($cryptoId, $amount)
    {
        $cryptos = self::get();
        $crypto = $cryptos[$cryptoId];

        // Round based on smallest unit of crypto
        $roundedAmount = round($amount, $crypto->get_round_precision(), PHP_ROUND_HALF_UP);

        // Forces displaying the number in decimal format, with as many zeroes as possible to display the smallest unit of crypto
        $formattedAmount = number_format($roundedAmount, $crypto->get_round_precision(), '.', '');

        // We probably have extra 0's on the right side of the string so trim those
        $amountWithoutZeroes = rtrim($formattedAmount, '0');

        // If it came out to an round whole number we have a dot on the right side, so take that off
        $amountWithoutTrailingDecimal = rtrim($amountWithoutZeroes, '.');

        return $amountWithoutTrailingDecimal;
    }

    public static function is_valid_wallet_address($cryptoId, $address)
    {
        if ($cryptoId === 'BTC') {
            return preg_match('/^[13][a-km-zA-HJ-NP-Z0-9]{24,42}|bc[a-z0-9]{8,87}/', $address);
        }
        if ($cryptoId === 'ETH') {
            return preg_match('/0x[a-fA-F0-9]{40,42}/', $address);
        }
        if ($cryptoId === 'ETHO') {
            return preg_match('/0x[a-fA-F0-9]{40,42}/', $address);
        }
        if ($cryptoId === 'XMR') {
            // return preg_match('/4[0-9AB][1-9A-HJ-NP-Za-km-z]{93}/', $address);

            // 2-15-2019 not testing Monero
            return strlen($address) > 30;
        }
        if ($cryptoId === 'DOGE') {
            return preg_match('/^D{1}[5-9A-HJ-NP-U]{1}[1-9A-HJ-NP-Za-km-z]{32}/', $address);
        }
        if ($cryptoId === 'LTC') {
            return preg_match('/^[LM3][a-km-zA-HJ-NP-Z1-9]{26,33}|l[a-z0-9]{8,87}/', $address);
        }
        if ($cryptoId === 'ZEC') {
            $isTAddr =  preg_match('/^t1[a-zA-Z0-9]{33,36}/', $address);
            $isZAddr = preg_match('/^z[a-zA-Z0-9]{90,96}/', $address);

            return $isTAddr || $isZAddr;
        }
        if ($cryptoId === 'BCH') {
            $isOldAddress = preg_match('/^[13][a-km-zA-HJ-NP-Z1-9]{25,42}/', $address);
            $isNewAddress1 = preg_match('/^(q|p)[a-z0-9]{41}/', $address);
            $isNewAddress2 = preg_match('/^(Q|P)[A-Z0-9]{41}/', $address);

            return $isOldAddress || $isNewAddress1 || $isNewAddress2;
        }
        if ($cryptoId === 'DASH') {
            return preg_match('/^X[1-9A-HJ-NP-Za-km-z]{33}/', $address);
        }
        if ($cryptoId === 'XRP') {
            return preg_match('/^r[0-9a-zA-Z]{28,37}/', $address);
        }
        if ($cryptoId === 'ONION') {
            return preg_match('/^D[0-9a-zA-Z]{33}/', $address);
        }
        if ($cryptoId === 'BLK') {
            return preg_match('/^B[0-9a-zA-Z]{32,36}/', $address);
        }
        if ($cryptoId === 'VRC') {
            return preg_match('/^V[0-9a-zA-Z]{32,36}/', $address);
        }
        if ($cryptoId === 'ETC') {
            return preg_match('/^0x[a-fA-F0-9]{40,42}/', $address);
        }
        if ($cryptoId === 'REP') {
            return preg_match('/^0x[a-fA-F0-9]{40,42}/', $address);
        }
        if ($cryptoId === 'BTG') {
            return preg_match('/^[AG][a-km-zA-HJ-NP-Z0-9]{26,42}|bt[a-z0-9]{8,87}/', $address);
        }
        if ($cryptoId === 'EOS') {
            return strlen($address) >= 1 && strlen($address) <= 12;
        }
        if ($cryptoId === 'BSV') {
            return preg_match('/^[13][a-km-zA-HJ-NP-Z0-9]{26,42}|q[a-z0-9]{9,88}/', $address);
        }
        if ($cryptoId === 'VET') {
            return preg_match('/^0x[a-fA-F0-9]{40,42}/', $address);
        }
        if ($cryptoId === 'TRX') {
            return preg_match('/^T[a-km-zA-HJ-NP-Z0-9]{26,42}/', $address);
        }
        if ($cryptoId === 'XLM') {
            return preg_match('/^G[A-Z0-9]{55}/', $address);
        }
        if ($cryptoId === 'QTUM') {
            return preg_match('/^Q[0-9a-zA-Z]{31,35}/', $address);
        }
        if ($cryptoId === 'ADA') {
            $match1 = preg_match('/^Ddz[0-9a-zA-Z]{80,120}/', $address);
            $match2 = preg_match('/^Ae2tdPwUPE[0-9a-zA-Z]{46,53}/', $address);

            return $match1 || $match2;
        }
        if ($cryptoId === 'XTZ') {
            return preg_match('/^tz1[0-9a-zA-Z]{30,39}/', $address);
        }
        if ($cryptoId === 'MLN') {
            return preg_match('/^0x[a-fA-F0-9]{40,42}/', $address);
        }
        if ($cryptoId === 'GNO') {
            return preg_match('/^0x[a-fA-F0-9]{40,42}/', $address);
        }
        if ($cryptoId === 'ONT') {
            return preg_match('/^A[0-9a-zA-Z]{31,35}/', $address);
        }
        if ($cryptoId === 'BAT') {
            return preg_match('/^0x[a-fA-F0-9]{40,42}/', $address);
        }
        if ($cryptoId === 'BCD') {
            return preg_match('/^1[0-9a-zA-Z]{31,35}/', $address);
        }
        if ($cryptoId === 'BCN') {
            return preg_match('/^2[0-9a-zA-Z]{91,99}/', $address);
        }
        if ($cryptoId === 'BNB') {
            return preg_match('/^bnb[a-zA-Z0-9]{37,48}/', $address) || preg_match('/^0x[a-fA-F0-9]{40,42}/', $address);
        }
        if ($cryptoId === 'DCR') {
            return preg_match('/^D[0-9a-zA-Z]{31,35}/', $address);
        }
        if ($cryptoId === 'DGB') {
            return preg_match('/^D[0-9a-zA-Z]{31,35}/', $address);
        }
        if ($cryptoId === 'HOT') {
            return preg_match('/^0x[a-fA-F0-9]{40,42}/', $address);
        }
        if ($cryptoId === 'LINK') {
            return preg_match('/^0x[a-fA-F0-9]{40,42}/', $address);
        }
        if ($cryptoId === 'LSK') {
            return preg_match('/^[0-9a-zA-Z]{17,22}L/', $address);
        }
        if ($cryptoId === 'MIOTA') {
            return preg_match('/^[0-9a-zA-Z]{85,95}/', $address);
        }
        if ($cryptoId === 'MKR') {
            return preg_match('/^0x[a-fA-F0-9]{40,42}/', $address);
        }
        if ($cryptoId === 'OMG') {
            return preg_match('/^0x[a-fA-F0-9]{40,42}/', $address);
        }
        if ($cryptoId === 'POT') {
            return preg_match('/^P[0-9a-zA-Z]{31,35}/', $address);
        }
        if ($cryptoId === 'WAVES') {
            return preg_match('/^3[0-9a-zA-Z]{31,35}/', $address);
        }
        if ($cryptoId === 'XEM') {
            return preg_match('/^N[0-9a-zA-Z]{35,45}/', $address);
        }
        if ($cryptoId === 'ZRX') {
            return preg_match('/^0x[a-fA-F0-9]{40,42}/', $address);
        }
        if ($cryptoId === 'GUSD') {
            return preg_match('/^0x[a-fA-F0-9]{40,42}/', $address);
        }
        if ($cryptoId === 'XMY') {
            return preg_match('/^[M45][a-zA-Z0-9]{31,38}/', $address);
        }
        if ($cryptoId === 'BTX') {
            return preg_match('/^[2s][a-km-zA-HJ-NP-Z0-9]{24,42}|btx[a-z0-9]{8,87}/', $address);
        }
        if ($cryptoId === 'GRS') {
            return preg_match('/^[F3][a-km-zA-HJ-NP-Z0-9]{24,42}/', $address);
        }
        if ($cryptoId === 'APL') {
            return preg_match('/^APL-[a-zA-Z0-9-]{8,42}/', $address);
        }
        if ($cryptoId === 'USDC') {
            return preg_match('/^0x[a-fA-F0-9]{40,42}/', $address);
        }

        NMM_Util::log(__FILE__, __LINE__, 'Invalid cryptoId, contact plug-in developer.');
        throw new Exception('Invalid cryptoId, contact plug-in developer.');
    }
}
