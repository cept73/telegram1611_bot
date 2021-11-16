<?php

namespace app\modules\listing;

use app\models\Chat;
use app\models\Listing;
use yii\db\Query;

class ListingRepository
{
    public function getLastChangeNum(): int
    {
        return (int) Listing::find()->max('change_num');
    }

    public function getLastChangeDate()
    {
        $lastChangeNum = $this->getLastChangeNum();
        return Listing::find()->select(['changed_at'])->where(['change_num' => $lastChangeNum])->scalar();
    }

    public function searchByTicker(string $ticker): array
    {
        return $this->querySearch($ticker)->all();
    }

    /**
     * @return Listing[]
     */
    public function all(): array
    {
        return $this->queryFromLastChange()->all();
    }

    /**
     * @return Query
     */
    private function queryFromLastChange(): Query
    {
        return Listing::find()->where(['change_num' => $this->getLastChangeNum()]);
    }

    private function querySearch(string $string): Query
    {
        $query = $this->queryFromLastChange();
        if (strtoupper($string) !== strtoupper(Chat::TICKER_SHOW_ALL)) {
            $query = $query->andWhere(['like', 'ticker', $string]);
        }

        return $query;
    }
}
