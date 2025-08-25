<?php

namespace App\Helpers;

class MakerHelper
{
    /**
     * 将日文制造商名称转换为英文别名
     */
    public static function toEnglish($japaneseName)
    {
        $mapping = config('maker_mapping');
        return $mapping[$japaneseName] ?? $japaneseName;
    }

    /**
     * 将英文别名转换为日文制造商名称
     */
    public static function toJapanese($englishName)
    {
        $mapping = config('maker_mapping');
        return $mapping[$englishName] ?? $englishName;
    }

    /**
     * 检查是否为有效的英文别名
     */
    public static function isValidEnglish($englishName)
    {
        $mapping = config('maker_mapping');
        return isset($mapping[$englishName]);
    }

    /**
     * 检查是否为有效的日文名称
     */
    public static function isValidJapanese($japaneseName)
    {
        $mapping = config('maker_mapping');
        return isset($mapping[$japaneseName]);
    }
}
