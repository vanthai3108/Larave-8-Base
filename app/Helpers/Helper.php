<?php

namespace App\Helpers;

use App\Models\HistoryImportFile;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Helper
{
    public static function formatDate($date, string $format = 'Y/m/d'): ?string
    {
        return is_null($date) ? null : (new DateTime($date))->format($format);
    }

    public static function replaceValueArray(array $data, $value = '', $valueReplace = null): array
    {
        array_walk_recursive($data, function (&$item) use ($value, $valueReplace) {
            // replace $value with $valueReplace
            $item = $value === $item ? $valueReplace : $item;
        });

        return $data;
    }

    /**
     * Convert data encoding
     * @param $content
     * @param $separator
     * @return array|array[]|false
     */
    public static function convertDataEncoding($content, $separator = ','): array|bool
    {
        if (mb_check_encoding($content, 'SJIS-win')) {
            $content = mb_convert_encoding($content, 'UTF-8', 'SJIS-win');
        } elseif (mb_check_encoding($content, 'SJIS')) {
            $content = mb_convert_encoding($content, 'UTF-8', 'SJIS');
        } else {
            return false;
        }

        $lineBreak = "\n";
        if (strstr($content, "\r\n")) {
            $lineBreak = "\r\n";
        } elseif (strstr($content, "\r")) {
            $lineBreak = "\r";
        }
        $content = rtrim($content, $lineBreak);

        return array_map(function ($d) use ($separator) {
            $csv = str_getcsv($d, $separator);

            foreach ($csv as $key => $value) {
                if ($value == '') {
                    $csv[$key] = null;
                }
            }

            return $csv;
        }, explode($lineBreak, $content));
    }

    /**
     * @param $value
     * @param $format
     *
     * @return string|null
     */
    public static function formatDateCsv($value, $format)
    {
        try {
            $value = (string) $value;
            $year = substr($value, 0, 4);
            $month = substr($value, 4, 2);
            $day = substr($value, 6, 2);
            $date = $year.'-'.$month.'-'.$day;
            $dateFormat = Carbon::parse($year.'-'.$month.'-'.$day);

            if ($dateFormat->format('Y-m-d') == $date) {
                return $dateFormat->format($format);
            }

            return;
        } catch (Exception $e) {
            return;
        }
    }

    public static function trimTrailingZeroes($nbr)
    {
        return strpos($nbr, '.') !== false ? rtrim(rtrim($nbr, '0'), '.') : $nbr;
    }

    public static function convertVoicedSoundUTF8ToSJIS($str)
    {
        $patterns = [
            // Hiragana
            '/\xE3\x81\x8B\xE3\x82\x99/', // か+○゛=> が
            '/\xE3\x81\x8D\xE3\x82\x99/', // き+○゛=> ぎ
            '/\xE3\x81\x8F\xE3\x82\x99/', // く+○゛=> ぐ
            '/\xE3\x81\x91\xE3\x82\x99/', // け+○゛=> げ
            '/\xE3\x81\x93\xE3\x82\x99/', // こ+○゛=> ご
            '/\xE3\x81\x95\xE3\x82\x99/', // さ+○゛=> ざ
            '/\xE3\x81\x97\xE3\x82\x99/', // し+○゛=> じ
            '/\xE3\x81\x99\xE3\x82\x99/', // す+○゛=> ず
            '/\xE3\x81\x9B\xE3\x82\x99/', // せ+○゛=> ぜ
            '/\xE3\x81\x9D\xE3\x82\x99/', // そ+○゛=> ぞ
            '/\xE3\x81\x9F\xE3\x82\x99/', // た+○゛=> だ
            '/\xE3\x81\xA1\xE3\x82\x99/', // ち+○゛=> ぢ
            '/\xE3\x81\xA4\xE3\x82\x99/', // つ+○゛=> づ
            '/\xE3\x81\xA6\xE3\x82\x99/', // て+○゛=> で
            '/\xE3\x81\xA8\xE3\x82\x99/', // と+○゛=> ど
            '/\xE3\x81\xAF\xE3\x82\x99/', // は+○゛=> ば
            '/\xE3\x81\xAF\xE3\x82\x9A/', // は+○゜=> ぱ
            '/\xE3\x81\xB2\xE3\x82\x99/', // ひ+○゛=> び
            '/\xE3\x81\xB2\xE3\x82\x9A/', // ひ+○゜=> ぴ
            '/\xE3\x81\xB5\xE3\x82\x99/', // ふ+○゛=> ぶ
            '/\xE3\x81\xB5\xE3\x82\x9A/', // ふ+○゜=> ぷ
            '/\xE3\x81\xB8\xE3\x82\x99/', // へ+○゛=> べ
            '/\xE3\x81\xB8\xE3\x82\x9A/', // へ+○゜=> ぺ
            '/\xE3\x81\xBB\xE3\x82\x99/', // ほ+○゛=> ぼ
            '/\xE3\x81\xBB\xE3\x82\x9A/', // ほ+○゜=> ぽ
            // Katakana
            '/\xE3\x82\xAB\xE3\x82\x99/', // カ+○゛=> ガ
            '/\xE3\x82\xAD\xE3\x82\x99/', // キ+○゛=> ギ
            '/\xE3\x82\xAF\xE3\x82\x99/', // ク+○゛=> グ
            '/\xE3\x82\xB1\xE3\x82\x99/', // ケ+○゛=> ゲ
            '/\xE3\x82\xB3\xE3\x82\x99/', // コ+○゛=> ゴ
            '/\xE3\x82\xB5\xE3\x82\x99/', // サ+○゛=> ザ
            '/\xE3\x82\xB7\xE3\x82\x99/', // シ+○゛=> ジ
            '/\xE3\x82\xB9\xE3\x82\x99/', // ス+○゛=> ズ
            '/\xE3\x82\xBB\xE3\x82\x99/', // セ+○゛=> ゼ
            '/\xE3\x82\xBD\xE3\x82\x99/', // ソ+○゛=> ゾ
            '/\xE3\x82\xBF\xE3\x82\x99/', // タ+○゛=> ダ
            '/\xE3\x83\x81\xE3\x82\x99/', // チ+○゛=> ヂ
            '/\xE3\x83\x84\xE3\x82\x99/', // ツ+○゛=> ヅ
            '/\xE3\x83\x86\xE3\x82\x99/', // テ+○゛=> デ
            '/\xE3\x83\x88\xE3\x82\x99/', // ト+○゛=> ド
            '/\xE3\x83\x8F\xE3\x82\x99/', // ハ+○゛=> バ
            '/\xE3\x83\x8F\xE3\x82\x9A/', // ハ+○゜=> パ
            '/\xE3\x83\x92\xE3\x82\x99/', // ヒ+○゛=> ビ
            '/\xE3\x83\x92\xE3\x82\x9A/', // ヒ+○゜=> ピ
            '/\xE3\x83\x95\xE3\x82\x99/', // フ+○゛=> ブ
            '/\xE3\x83\x95\xE3\x82\x9A/', // フ+○゜=> プ
            '/\xE3\x83\x98\xE3\x82\x99/', // ヘ+○゛=> ベ
            '/\xE3\x83\x98\xE3\x82\x9A/', // ヘ+○゜=> ペ
            '/\xE3\x83\x9B\xE3\x82\x99/', // ホ+○゛=> ボ
            '/\xE3\x83\x9B\xE3\x82\x9A/', // ホ+○゜=> ポ
        ];

        $replacements = [
            // Hiragana
            'が', 'ぎ', 'ぐ', 'げ', 'ご',
            'ざ', 'じ', 'ず', 'ぜ', 'ぞ',
            'だ', 'ぢ', 'づ', 'で', 'ど',
            'ば', 'ぱ', 'び', 'ぴ', 'ぶ',
            'ぷ', 'べ', 'ぺ', 'ぼ', 'ぽ',
            // Katakana
            'ガ', 'ギ', 'グ', 'ゲ', 'ゴ',
            'ザ', 'ジ', 'ズ', 'ゼ', 'ゾ',
            'ダ', 'ヂ', 'ヅ', 'デ', 'ド',
            'バ', 'パ', 'ビ', 'ピ', 'ブ',
            'プ', 'ベ', 'ペ', 'ボ', 'ポ',
        ];

        return preg_replace($patterns, $replacements, $str);
    }

    /**
     * Format data csv from UTF-8 to SJIS.
     *
     * @param $rows
     * @param $header
     *
     * @return array|false|string|string[]|null
     */
    public static function formatCsvUTF8ToSJIS($rows, $header = [])
    {
        if (count($header)) {
            array_unshift($rows, $header);
        }
        $fp = fopen('php://temp', 'r+b');

        foreach ($rows as $fields) {
            fputcsv($fp, $fields);
        }
        rewind($fp);
        // Convert CRLF
        $tmp = str_replace(PHP_EOL, "\r\n", stream_get_contents($fp));
        fclose($fp);
        // Convert raw data from UTF-8 to Shift-JS
        $tmp = static::convertVoicedSoundUTF8ToSJIS($tmp);

        return mb_convert_encoding($tmp, 'SJIS', 'UTF-8');
    }

    /**
     * Get file content from ftp
     * @param $folders
     * @param $filePath
     * @param $channel
     * @return array
     * @throws Exception
     */
    public static function getFileContentFromFtp($folders, $filePath, $channel): array
    {
        $fileName = Str::afterLast($filePath, '/');
        Log::channel($channel)->info('--- Start get file from ftp ---');
        $retries = config('common.csv.retries_ftp_error');
        $sleepFtpError = config('common.csv.sleep_file_not_found');
        while ($retries > 0) {
            try {
                $data = [];
                foreach ($folders as $folder) {
                    $files = Storage::files($folder);
                    if (count($files)) {
                        $listFilesSorted = collect($files)->filter(function ($file) use ($fileName) {
                            return Str::afterLast($file, '/') > $fileName;
                        })->toArray();
                        if (count($listFilesSorted)) {
                            sort($listFilesSorted);
                            $data[] = $listFilesSorted[0];
                        }
                    }
                }
                if (!count($data)) {
                    sleep($sleepFtpError);
                    $retries--;
                    continue;
                } elseif (count($data) === 1) {
                    $filePathImport = $data[0];
                } else {
                    $filePathImport = Str::afterLast($data[0], '/') < Str::afterLast($data[1], '/')
                        ? $data[0]
                        : $data[1];
                }
                Log::channel($channel)->info('File path: ' . $filePathImport);
                $fileSize = Storage::size($filePathImport);
                if (!$fileSize) {
                    Log::channel($channel)->info('File size = 0');
                    return [
                        'file_path' => $filePathImport,
                        'data' => [],
                        'status' => HistoryImportFile::SUCCESS
                    ];
                }
                $fileContent = Storage::get($filePathImport);
                Log::channel($channel)->info('--- End get file from ftp ---');
                $result = static::convertDataEncoding($fileContent, config('common.csv.import_separator'));
                if ($result === false) {
                    Log::channel($channel)->error(__('messages.csv.encoding'));
                    return [
                        'file_path' => $filePathImport,
                        'data' => [],
                        'status' => HistoryImportFile::FAIL
                    ];
                }

                return [
                    'file_path' => $filePathImport,
                    'data' => $result,
                    'status' => HistoryImportFile::SUCCESS
                ];
            } catch (\Exception $exception) {
                Log::channel($channel)->error($exception->getMessage());
                $retries--;
                if ($retries > 0) {
                    sleep($sleepFtpError);
                }
            }
        }
        throw new Exception(__('messages.csv.ftp_error'));
    }

    /**
     * @param $dateTime
     * @return bool
     */
    public static function isThirdSundayOfMonth($dateTime): bool
    {
        $monthYear = $dateTime->format(config('common.time.format_F_Y'));
        $formatYmd = config('common.time.format_Y-m-d');
        $thirdSunday = strtolower(date($formatYmd, strtotime('third sunday of ' . $monthYear)));
        if ($dateTime->format($formatYmd) === $thirdSunday) {
            return true;
        }

        return false;
    }

    public static function messageApi($code, $message)
    {
        return json_encode([
            'code' => $code,
            'message' => $message,
        ]);
    }
}
