<?php

namespace app\components;

use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Yii;
use yii\base\InvalidArgumentException;

class Formatter extends \yii\i18n\Formatter
{
     /**
     * @var string|null the time zone to use for formatting time and date values.
     *
     * This can be any value that may be passed to [date_default_timezone_set()](https://www.php.net/manual/en/function.date-default-timezone-set.php)
     * e.g. `UTC`, `Europe/Berlin` or `America/Chicago`.
     * Refer to the [php manual](https://www.php.net/manual/en/timezones.php) for available time zones.
     * If this property is not set, [[\yii\base\Application::timeZone]] will be used.
     *
     * Note that the default time zone for input data is assumed to be UTC by default if no time zone is included in the input date value.
     * If you store your data in a different time zone in the database, you have to adjust [[defaultTimeZone]] accordingly.
     */
    public $timeZone;

    /**
     * Formats the value as the time interval between a date and now in human readable form.
     *
     * This method can be used in three different ways:
     *
     * 1. Using a timestamp that is relative to `now`.
     * 2. Using a timestamp that is relative to the `$referenceTime`.
     * 3. Using a `DateInterval` object.
     *
     * @param int|string|DateTime|DateTimeInterface|DateInterval|null $value the value to be formatted. The following
     * types of value are supported:
     *
     * - an integer representing a UNIX timestamp
     * - a string that can be [parsed to create a DateTime object](https://www.php.net/manual/en/datetime.formats.php).
     *   The timestamp is assumed to be in [[defaultTimeZone]] unless a time zone is explicitly given.
     * - a PHP [DateTime](https://www.php.net/manual/en/class.datetime.php) object
     * - a PHP DateInterval object (a positive time interval will refer to the past, a negative one to the future)
     *
     * @param int|string|DateTime|DateTimeInterface|null $referenceTime if specified the value is used as a reference time instead of `now`
     * when `$value` is not a `DateInterval` object.
     * @return string|null the formatted result.
     * @throws InvalidArgumentException if the input value can not be evaluated as a date value.
     */
    public function asRelativeTime($value, $referenceTime = null): ?string
    {
        if ($value === null) {
            return $this->nullDisplay;
        }

        if ($this->timeZone === null) {
            $this->timeZone = Yii::$app->getTimeZone();
        }

        if ($value instanceof DateInterval) {
            $interval = $value;
        } else {
            $timestamp = $this->normalizeDatetimeValue($value);

            if (!$timestamp instanceof DateTime) {
                $timestamp = $timestamp[0];
            }

            $timeZone = new DateTimeZone($this->timeZone);

            if ($referenceTime === null) {
                $dateNow = new DateTime('now', $timeZone);
            } else {
                $dateNow = $this->normalizeDatetimeValue($referenceTime);

                if (!$dateNow instanceof DateTime) {
                    $dateNow = $dateNow[0];
                }

                $dateNow->setTimezone($timeZone);
            }

            $dateThen = $timestamp->setTimezone($timeZone);

            $interval = $dateThen->diff($dateNow);
        }

        if ($interval->invert) {
            if ($interval->y >= 1) {
                return Yii::t('app', 'in {delta, plural, other{# y}}', ['delta' => $interval->y], $this->language);
            }
            if ($interval->m >= 1) {
                return Yii::t('app', 'in {delta, plural, other{# mon.}}', ['delta' => $interval->m], $this->language);
            }
            if ($interval->d >= 1) {
                return Yii::t('app', 'in {delta, plural, other{# d}}', ['delta' => $interval->d], $this->language);
            }
            if ($interval->h >= 1) {
                return Yii::t('app', 'in {delta, plural, other{# h}}', ['delta' => $interval->h], $this->language);
            }
            if ($interval->i >= 1) {
                return Yii::t('app', 'in {delta, plural, other{# min}}', ['delta' => $interval->i], $this->language);
            }

            return Yii::t('app', 'in {delta, plural, other{# s}}', ['delta' => $interval->s], $this->language);
        }

        if ($interval->y >= 1) {
            return Yii::t('app', '{delta, plural, other{# y}}', ['delta' => $interval->y], $this->language);
        }
        if ($interval->m >= 1) {
            return Yii::t('app', '{delta, plural, other{# mon.}}', ['delta' => $interval->m], $this->language);
        }
        if ($interval->d >= 1) {
            return Yii::t('app', '{delta, plural, other{# d}}', ['delta' => $interval->d], $this->language);
        }
        if ($interval->h >= 1) {
            return Yii::t('app', '{delta, plural, other{# h}}', ['delta' => $interval->h], $this->language);
        }
        if ($interval->i >= 1) {
            return Yii::t('app', '{delta, plural, other{# min}}', ['delta' => $interval->i], $this->language);
        }

        return Yii::t('yii', '{delta, plural, other{# s}}', ['delta' => $interval->s], $this->language);
    }
}
