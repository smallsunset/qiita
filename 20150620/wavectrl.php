<?php

	a();
	b();
	c();

	function a() {

	    $w1 = new WavCtrl();
	    $w1->LoadFile('_あ.wav');

	    $w2 = new WavCtrl();
	    $w2->LoadFile('_い.wav');

	    $w3 = new WavCtrl();
	    $w3->LoadFile('_う.wav');

	    $w1->WaveConnect($w2);
	    $w1->WaveConnect($w3);

	    $w1->SaveFile('step1.wav');

	}

	function b() {

	    $w1 = new WavCtrl();
	    $w1->LoadFile('_あ.wav');

	    $w2 = new WavCtrl();
	    $w2->LoadFile('_い.wav');

	    $w3 = new WavCtrl();
	    $w3->LoadFile('_う.wav');

	    $w1->WaveShift(261);
	    $w2->WaveShift(329);
	    $w3->WaveShift(391);

	    $w1->WaveConnect($w2);
	    $w1->WaveConnect($w3);

	    $w1->SaveFile('step2.wav');

	}

	function c() {

	    $w1 = new WavCtrl();
	    $w1->LoadFile('_あ.wav');

	    $w2 = new WavCtrl();
	    $w2->LoadFile('_あ.wav');

	    $w3 = new WavCtrl();
	    $w3->LoadFile('_あ.wav');

	    $w1->WaveShift(261);
	    $w2->WaveShift(329);
	    $w3->WaveShift(391);

	    $w1->WaveMix($w2);
	    $w1->WaveMix($w3);

	    $w1->SaveFile('step3.wav');

	}

    class WavCtrl {

        var $_d     = '';   // データ

        var $datasize   = 0;    // データサイズ
        var $fmtid  = 0;    // フォーマットID
        var $chsize = 0;    // チャンネル数
        var $freq   = 0;    // サンプリング周波数

        function LoadFile($fn) {

            $this->_d = file_get_contents($fn);

            // 先頭4バイトが、RIFF でなければ、WAV ファイルではない。
            if (substr($this->_d, 0, 4) != 'RIFF') {
                return false;
            }

            // chunk 識別コード WAVE が存在するかどうか調査
            if (substr($this->_d, 8, 4) != 'WAVE') {
                return false;
            }

            // chunk 識別コード fmt が存在するかどうか調査
            if (substr($this->_d, 12, 4) != 'fmt ') {
                return false;
            }

            // chunk 識別コード data が存在するかどうか調査
            if (substr($this->_d, 36, 4) != 'data') {
                return false;
            }

            // フォーマットID を取得します
            // リニアPCMだけを対象にするので、それ以外はエラー
            $d = unpack('v', substr($this->_d, 20, 2));
            $this->fmtid = $d[1];

            if ($this->fmtid != 1) {
                return false;
            }

            // チャンネル数を取得
            // モノラルチャンネルだけを対象にします
            $d = unpack('v', substr($this->_d, 22, 2));
            $this->chsize = $d[1];

            if ($this->fmtid != 1) {
                return false;
            }

            // サンプリング周波数を取得
            // 44100hz のみを対象とします
            $d = unpack('V', substr($this->_d, 24, 4));
            $this->freq = $d[1];

            // データサイズを取得
            $d = unpack('V', substr($this->_d, 40, 4));
            $this->datasize = $d[1];

        }

        function SaveFile($p1) {

            file_put_contents($p1, $this->_d);

        }

        function WaveConnect(&$p1) {

            // WAVE ファイルのデータ部分だけ結合します
            $this->_d = $this->_d . substr($p1->_d, 44, $p1->datasize);

            // データサイズを更新します
            $this->datasize = strlen($this->_d) - 44;

            // 実際のデータのサイズも更新します
            $d = pack('V', strlen($this->_d) - 8);
            $this->_d[4] = $d[0];
            $this->_d[5] = $d[1];
            $this->_d[6] = $d[2];
            $this->_d[7] = $d[3];

            $d = pack('V', $this->datasize);
            $this->_d[40] = $d[0];
            $this->_d[41] = $d[1];
            $this->_d[42] = $d[2];
            $this->_d[43] = $d[3];

        }

        // $p1 変換後の周波数を指定します。
        function WaveShift($p1) {

            $f1 = $p1 / 440;
            $f2 = 0;

            $dst = substr($this->_d, 0, 44);

            while(1) {

                $d = substr($this->_d, 44 + floor($f2) * 2, 2);

                $dst .= $d[0];
                $dst .= $d[1];

                if ($f2 > $this->datasize) {
                    break;
                }

                $f2 += $f1;

            }

            // データ更新
            $this->_d = $dst;
            $this->datasize = strlen($this->_d) - 44;

            // 実際のデータのサイズも更新します
            $d = pack('V', strlen($this->_d) -  8);

            $this->_d[4] = $d[0];
            $this->_d[5] = $d[1];
            $this->_d[6] = $d[2];
            $this->_d[7] = $d[3];

            $d = pack('V', $this->datasize);

            $this->_d[40] = $d[0];
            $this->_d[41] = $d[1];
            $this->_d[42] = $d[2];
            $this->_d[43] = $d[3];

            // 変数解放
            unset($dst);

        }

		function WaveMix(&$p1) {

			$f2 = 0;

			while(1) {

				$src1 = 0;
				$src2 = 0;

				if ($f2 < $this->datasize) {
					$d = unpack('s', substr($this->_d, 44 + $f2, 2));
					$src1 = $d[1];
				}

				if ($f2 < $p1->datasize) {
					$d = unpack('s', substr($p1->_d,   44 + $f2, 2));
					$src2 = $d[1];
				}

				// ミックスします
				$src3 = floor($src1 + $src2);

				// 音割れ防止
				if ($src3 < -32768) {
					$src3 = -32768;
				}

				// 音割れ防止
				if ($src3 > 32767) {
					$src3 = 32767;
				}

				$d = pack('v', $src3);

				$this->_d[44 + $f2    ] = $d[0];
				$this->_d[44 + $f2 + 1] = $d[1];

				if (($f2 > $this->datasize) and ($f2 > $p1->datasize)) {
					break;
				}

				$f2 += 2;

			}

		}

    }
