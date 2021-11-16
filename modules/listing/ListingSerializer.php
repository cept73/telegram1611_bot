<?php

namespace app\modules\listing;

use app\models\Chat;
use app\models\Listing;
use Yii;
use yii\base\InvalidConfigException;

class ListingSerializer
{
    /**
     * @return string
     * @throws InvalidConfigException
     */
    private static function getLastUpdateText(): string
    {
        /** @var ListingRepository $listingRepository */
        $listingRepository = Yii::createObject(ListingRepository::class);
        return sprintf(Chat::TEXT_LAST_UPDATE, $listingRepository->getLastChangeDate());
    }

    /**
     * @var Listing[] $listingArray
     * @return string
     * @throws InvalidConfigException
     */
    public static function getList(array $listingArray): string
    {
        if (empty($listingArray)) {
            return '';
        }

        $result = '';
        foreach ($listingArray as $listing) {
            if ($result) {
                $result .= ', ';
            }
            $result .= $listing->ticker;
        }
        $result .= "\n" . Chat::TICKER_SHOW_ALL . ' - вывод всех';
        $result .= "\n\n" . self::getLastUpdateText();

        return $result;
    }

    /**
     * @var Listing[] $listingArray
     * @return string
     * @throws InvalidConfigException
     */
    public static function getInfo(array $listingArray): string
    {
        if (empty($listingArray)) {
            return '';
        }

        $result = '_Ticker | Assets | Loans | Ratio1 | Cap | Loans | Ratio2 | Mark1 | Mark2_';
        foreach ($listingArray as $listing) {
            $result .= "\n\n" . self::getInfoOne($listing);
        }
        $result .= "\n\n" . self::getLastUpdateText();

        return $result;
    }

    /**
     * @var Listing $listing
     * @return string
     */
    public static function getInfoOne(Listing $listing): string
    {
        $ratio1     = sprintf('%.2f', $listing->loans / $listing->assets * 100);
        $ratio2     = sprintf('%.2f', $listing->loans / $listing->cap * 100);
        $isHalalByMark1 = (float)$ratio1 < Listing::RATIO1_HALAL_PERCENT;
        $isHalalByMark2 = (float)$ratio2 < Listing::RATIO2_HALAL_PERCENT;
        $verdict1   = $isHalalByMark1 ? Listing::TEXT_HALAL : '`' . Listing::TEXT_HARAM . '`';
        $verdict2   = $isHalalByMark2 ? Listing::TEXT_HALAL : '`' . Listing::TEXT_HARAM . '`';
        $ticker     = $isHalalByMark1 && $isHalalByMark2 ? $listing->ticker : "`{$listing->ticker}`";

        return "{$ticker} `|` `{$listing->assets}` `|` `{$listing->loans}` `|` `{$ratio1}%` `|`"
	        . " `{$listing->cap}` | `{$listing->loans}` | `{$ratio2}%` `|` {$verdict1} `|` {$verdict2}";
    }
}
