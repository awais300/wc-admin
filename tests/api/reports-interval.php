<?php
/**
 * Reports Interval Test
 *
 * @package WC Admin
 * @since x.x.0
 */
class WC_Tests_Reports_Interval_Stats extends WC_Unit_Test_Case {

	/**
	 * Restore UTC on failire.
	 */
	public function tearDown() {
		parent::tearDown();
		// @codingStandardsIgnoreStart
		date_default_timezone_set( 'UTC' );
		// @codingStandardsIgnoreEnd
		update_option( 'gmt_offset', 0 );
		update_option( 'timezone_string', '' );
	}

	public function onNotSuccessfulTest( Throwable $e ) {
		// @codingStandardsIgnoreStart
		date_default_timezone_set( 'UTC' );
		// @codingStandardsIgnoreEnd
		update_option( 'gmt_offset', 0 );
		update_option( 'timezone_string', '' );
		parent::onNotSuccessfulTest( $e );
	}

	/**
	 * Test quarter function.
	 */
	public function test_quarter() {
		$datetime = new DateTime( '2017-12-31T00:00:00Z' );
		$this->assertEquals( 4, WC_Admin_Reports_Interval::quarter( $datetime ) );

		$datetime = new DateTime( '2018-01-01T00:00:00Z' );
		$this->assertEquals( 1, WC_Admin_Reports_Interval::quarter( $datetime ) );

		$datetime = new DateTime( '2018-03-31T23:59:59Z' );
		$this->assertEquals( 1, WC_Admin_Reports_Interval::quarter( $datetime ) );

		$datetime = new DateTime( '2018-04-01T00:00:00Z' );
		$this->assertEquals( 2, WC_Admin_Reports_Interval::quarter( $datetime ) );

		$datetime = new DateTime( '2018-06-30T23:59:59Z' );
		$this->assertEquals( 2, WC_Admin_Reports_Interval::quarter( $datetime ) );

		$datetime = new DateTime( '2018-07-01T00:00:00Z' );
		$this->assertEquals( 3, WC_Admin_Reports_Interval::quarter( $datetime ) );

		$datetime = new DateTime( '2018-09-30T23:59:59Z' );
		$this->assertEquals( 3, WC_Admin_Reports_Interval::quarter( $datetime ) );

		$datetime = new DateTime( '2018-10-01T00:00:00Z' );
		$this->assertEquals( 4, WC_Admin_Reports_Interval::quarter( $datetime ) );

		$datetime = new DateTime( '2018-12-31T23:59:59Z' );
		$this->assertEquals( 4, WC_Admin_Reports_Interval::quarter( $datetime ) );

		update_option( 'timezone_string', 'Europe/Berlin' );
		$datetime = new DateTime( '2018-12-31T23:59:59Z' );
		$this->assertEquals( 1, WC_Admin_Reports_Interval::quarter( $datetime ) ); // Berlin is already past midnight.

		$datetime = new DateTime( '2018-12-31T23:59:59+02:00' );
		$this->assertEquals( 4, WC_Admin_Reports_Interval::quarter( $datetime ) );

	}

	/**
	 * Test simple week number function.
	 */
	public function test_simple_week_number() {
		$expected_week_no = array(
			'2010-12-24' => array(
				1 => 52,
				2 => 52,
				3 => 52,
				4 => 52,
				5 => 52,
				6 => 52,
				7 => 52,
			),
			'2010-12-25' => array(
				1 => 52,
				2 => 52,
				3 => 52,
				4 => 52,
				5 => 52,
				6 => 53,
				7 => 52,
			),
			'2010-12-26' => array(
				1 => 52,
				2 => 52,
				3 => 52,
				4 => 52,
				5 => 52,
				6 => 53,
				7 => 53,
			),
			'2010-12-27' => array(
				1 => 53,
				2 => 52,
				3 => 52,
				4 => 52,
				5 => 52,
				6 => 53,
				7 => 53,
			),
			'2010-12-28' => array(
				1 => 53,
				2 => 53,
				3 => 52,
				4 => 52,
				5 => 52,
				6 => 53,
				7 => 53,
			),
			'2010-12-29' => array(
				1 => 53,
				2 => 53,
				3 => 53,
				4 => 52,
				5 => 52,
				6 => 53,
				7 => 53,
			),
			'2010-12-30' => array(
				1 => 53,
				2 => 53,
				3 => 53,
				4 => 53,
				5 => 52,
				6 => 53,
				7 => 53,
			),
			'2010-12-31' => array(
				1 => 53,
				2 => 53,
				3 => 53,
				4 => 53,
				5 => 53,
				6 => 53,
				7 => 53,
			),
			'2011-01-01' => array(
				1 => 1,
				2 => 1,
				3 => 1,
				4 => 1,
				5 => 1,
				6 => 1,
				7 => 1,
			),
			'2011-01-02' => array(
				1 => 1,
				2 => 1,
				3 => 1,
				4 => 1,
				5 => 1,
				6 => 1,
				7 => 2,
			),
			'2011-01-03' => array(
				1 => 2,
				2 => 1,
				3 => 1,
				4 => 1,
				5 => 1,
				6 => 1,
				7 => 2,
			),
			'2011-01-04' => array(
				1 => 2,
				2 => 2,
				3 => 1,
				4 => 1,
				5 => 1,
				6 => 1,
				7 => 2,
			),
			'2011-01-05' => array(
				1 => 2,
				2 => 2,
				3 => 2,
				4 => 1,
				5 => 1,
				6 => 1,
				7 => 2,
			),
			'2011-01-06' => array(
				1 => 2,
				2 => 2,
				3 => 2,
				4 => 2,
				5 => 1,
				6 => 1,
				7 => 2,
			),
			'2011-01-07' => array(
				1 => 2,
				2 => 2,
				3 => 2,
				4 => 2,
				5 => 2,
				6 => 1,
				7 => 2,
			),
			'2011-01-08' => array(
				1 => 2,
				2 => 2,
				3 => 2,
				4 => 2,
				5 => 2,
				6 => 2,
				7 => 2,
			),
			'2011-01-09' => array(
				1 => 2,
				2 => 2,
				3 => 2,
				4 => 2,
				5 => 2,
				6 => 2,
				7 => 3,
			),
			'2011-01-10' => array(
				1 => 3,
				2 => 2,
				3 => 2,
				4 => 2,
				5 => 2,
				6 => 2,
				7 => 3,
			),
			'2011-12-26' => array(
				1 => 53,
				2 => 52,
				3 => 52,
				4 => 52,
				5 => 52,
				6 => 52,
				7 => 53,
			),
			'2011-12-27' => array(
				1 => 53,
				2 => 53,
				3 => 52,
				4 => 52,
				5 => 52,
				6 => 52,
				7 => 53,
			),
			'2011-12-28' => array(
				1 => 53,
				2 => 53,
				3 => 53,
				4 => 52,
				5 => 52,
				6 => 52,
				7 => 53,
			),
			'2011-12-29' => array(
				1 => 53,
				2 => 53,
				3 => 53,
				4 => 53,
				5 => 52,
				6 => 52,
				7 => 53,
			),
			'2011-12-30' => array(
				1 => 53,
				2 => 53,
				3 => 53,
				4 => 53,
				5 => 53,
				6 => 52,
				7 => 53,
			),
			'2011-12-31' => array(
				1 => 53,
				2 => 53,
				3 => 53,
				4 => 53,
				5 => 53,
				6 => 53,
				7 => 53,
			),
			'2012-01-01' => array(
				1 => 1,
				2 => 1,
				3 => 1,
				4 => 1,
				5 => 1,
				6 => 1,
				7 => 1,
			),
			'2012-01-02' => array(
				1 => 2,
				2 => 1,
				3 => 1,
				4 => 1,
				5 => 1,
				6 => 1,
				7 => 1,
			),
			'2012-01-03' => array(
				1 => 2,
				2 => 2,
				3 => 1,
				4 => 1,
				5 => 1,
				6 => 1,
				7 => 1,
			),
			'2012-01-04' => array(
				1 => 2,
				2 => 2,
				3 => 2,
				4 => 1,
				5 => 1,
				6 => 1,
				7 => 1,
			),
			'2012-01-05' => array(
				1 => 2,
				2 => 2,
				3 => 2,
				4 => 2,
				5 => 1,
				6 => 1,
				7 => 1,
			),
			'2012-01-06' => array(
				1 => 2,
				2 => 2,
				3 => 2,
				4 => 2,
				5 => 2,
				6 => 1,
				7 => 1,
			),
		);

		foreach ( $expected_week_no as $date => $week_numbers ) {
			for ( $first_day_of_week = 1; $first_day_of_week <= 7; $first_day_of_week++ ) {
				$datetime = new DateTime( $date );
				$this->assertEquals( $expected_week_no[ $date ][ $first_day_of_week ], WC_Admin_Reports_Interval::simple_week_number( $datetime, $first_day_of_week ), "First day of week: $first_day_of_week; Date: $date" );
			}
		}

		// Berlin is UTC+1 in winter and UTC+2 in summer, until our lovely DST change will be taken away from us.
		update_option( 'timezone_string', 'Europe/Berlin' );
		$expected_week_no = array(
			'2010-12-24T23:45:00Z' => array(
				1 => 52,
				2 => 52,
				3 => 52,
				4 => 52,
				5 => 52,
				6 => 53,
				7 => 52,
			),
			'2010-12-25T23:45:00Z' => array(
				1 => 52,
				2 => 52,
				3 => 52,
				4 => 52,
				5 => 52,
				6 => 53,
				7 => 53,
			),
			'2010-12-31T23:45:00Z' => array(
				1 => 1,
				2 => 1,
				3 => 1,
				4 => 1,
				5 => 1,
				6 => 1,
				7 => 1,
			),
			'2011-01-01T23:45:00Z' => array(
				1 => 1,
				2 => 1,
				3 => 1,
				4 => 1,
				5 => 1,
				6 => 1,
				7 => 2,
			),
			'2011-01-01T23:45:00+03:00' => array(
				1 => 1,
				2 => 1,
				3 => 1,
				4 => 1,
				5 => 1,
				6 => 1,
				7 => 1,
			),
			'2011-01-01T00:45:00+03:00' => array(
				1 => 53,
				2 => 53,
				3 => 53,
				4 => 53,
				5 => 53,
				6 => 53,
				7 => 53,
			),
		);

		foreach ( $expected_week_no as $date => $week_numbers ) {
			for ( $first_day_of_week = 1; $first_day_of_week <= 7; $first_day_of_week++ ) {
				$datetime = new DateTime( $date );
				$this->assertEquals( $expected_week_no[ $date ][ $first_day_of_week ], WC_Admin_Reports_Interval::simple_week_number( $datetime, $first_day_of_week ), "First day of week: $first_day_of_week; Date: $date" );
			}
		}
	}

	/**
	 * Testing ISO week number function.
	 */
	public function test_ISO_week_no() {
		$expected_week_no = array(
			'2010-12-24' => 51,
			'2010-12-25' => 51,
			'2010-12-26' => 51,
			'2010-12-27' => 52,
			'2010-12-28' => 52,
			'2010-12-29' => 52,
			'2010-12-30' => 52,
			'2010-12-31' => 52,
			'2011-01-01' => 52,
			'2011-01-02' => 52,
			'2011-01-03' => 1,
			'2011-01-04' => 1,
			'2011-01-05' => 1,
			'2011-01-06' => 1,
			'2011-01-07' => 1,
			'2011-01-08' => 1,
			'2011-01-09' => 1,
			'2011-01-10' => 2,
			'2011-12-26' => 52,
			'2011-12-27' => 52,
			'2011-12-28' => 52,
			'2011-12-29' => 52,
			'2011-12-30' => 52,
			'2011-12-31' => 52,
			'2012-01-01' => 52,
			'2012-01-02' => 1,
			'2012-01-03' => 1,
			'2012-01-04' => 1,
			'2012-01-05' => 1,
			'2012-01-06' => 1,
		);
		foreach ( $expected_week_no as $date => $week_numbers ) {
			$datetime = new DateTime( $date );
			$this->assertEquals( $expected_week_no[ $date ], WC_Admin_Reports_Interval::week_number( $datetime, 1 ), "ISO week number for date: $date" );
		}

		update_option( 'timezone_string', 'Europe/Berlin' );
		$expected_week_no = array(
			'2010-12-26T23:45:59+02' => 51,
			'2010-12-26T23:45:59Z'   => 52,
			'2010-12-27T23:45:59+02' => 52,
			'2010-12-27T23:45:59Z'   => 52,

			'2011-01-02T23:45:59+02' => 52,
			'2011-01-02T23:45:59Z'   => 1,
			'2011-01-03T23:45:59+02' => 1,
			'2011-01-03T23:45:59Z'   => 1,
		);
		foreach ( $expected_week_no as $date => $week_numbers ) {
			$datetime = new DateTime( $date );
			$this->assertEquals( $expected_week_no[ $date ], WC_Admin_Reports_Interval::week_number( $datetime, 1 ), "ISO week number for date: $date" );
		}
	}

	/**
	 * Test function counting intervals between two datetimes.
	 */
	public function test_intervals_between() {
		// Please note that all intervals are inclusive on both sides.
		$test_settings = array(
			// 0 interval length, should just return 1.
			array(
				'start'      => '2017-12-24T11:00:00Z',
				'end'        => '2017-12-24T11:00:00Z',
				'week_start' => 1,
				'intervals'  => array(
					'hour'    => 1,
					'day'     => 1,
					'week'    => 1,
					'month'   => 1,
					'quarter' => 1,
					'year'    => 1,
				),
			),
			// <1 hour interval length -> should return 1 for all
			array(
				'start'      => '2017-12-24T11:00:00Z',
				'end'        => '2017-12-24T11:40:00Z',
				'week_start' => 1,
				'intervals'  => array(
					'hour'    => 1,
					'day'     => 1,
					'week'    => 1,
					'month'   => 1,
					'quarter' => 1,
					'year'    => 1,
				),
			),
			// 1.66 hour interval length -> 2 hours, 1 for the rest
			array(
				'start'      => '2017-12-24T11:00:00Z',
				'end'        => '2017-12-24T12:40:00Z',
				'week_start' => 1,
				'intervals'  => array(
					'hour'    => 2,
					'day'     => 1,
					'week'    => 1,
					'month'   => 1,
					'quarter' => 1,
					'year'    => 1,
				),
			),
			// 23:59:59 h:m:s interval -> 24 hours, 1 for the rest
			array(
				'start'      => '2017-12-24T11:00:00Z',
				'end'        => '2017-12-25T10:59:59Z',
				'week_start' => 1,
				'intervals'  => array(
					'hour'    => 24,
					'day'     => 1,
					'week'    => 2,
					'month'   => 1,
					'quarter' => 1,
					'year'    => 1,
				),
			),
			// 24 hour inclusive interval -> 25 hours, 2 days, 1 for the rest
			array(
				'start'      => '2017-12-24T11:00:00Z',
				'end'        => '2017-12-25T11:00:00Z',
				'week_start' => 1,
				'intervals'  => array(
					'hour'    => 25,
					'day'     => 2,
					'week'    => 2,
					'month'   => 1,
					'quarter' => 1,
					'year'    => 1,
				),
			),
			// 1 month interval spanning 1 month -> 720 hours, 30 days, 5 iso weeks, 1 months
			array(
				'start'      => '2017-11-01T00:00:00Z',
				'end'        => '2017-11-30T23:59:59Z',
				'week_start' => 1,
				'intervals'  => array(
					'hour'    => 720,
					'day'     => 30,
					'week'    => 5,
					'month'   => 1,
					'quarter' => 1,
					'year'    => 1,
				),
			),
			// 1 month interval spanning 2 months, but 1 quarter and 1 year -> 721 hours, 31 days, 5 iso weeks, 2 months
			array(
				'start'      => '2017-11-24T11:00:00Z',
				'end'        => '2017-12-24T11:00:00Z',
				'week_start' => 1,
				'intervals'  => array(
					'hour'    => 30 * 24 + 1, // 30 full days + 1 second from next hour
					'day'     => 31,
					'week'    => 5,
					'month'   => 2,
					'quarter' => 1,
					'year'    => 1,
				),
			),
			// 1 month + 14 hour interval spanning 2 months in 1 quarter -> 735 hours, 31 days, 6 iso weeks, 2 months
			array(
				'start'      => '2017-11-24T11:00:00Z',
				'end'        => '2017-12-25T01:00:00Z',
				'week_start' => 1,
				'intervals'  => array(
					'hour'    => 30 * 24 + 14 + 1, // 30 full days + 14 full hours + 1 second from next hour
					'day'     => 31,
					'week'    => 6,
					'month'   => 2,
					'quarter' => 1,
					'year'    => 1,
				),
			),
			// 1 month interval spanning 2 months and 2 quarters, 1 year -> 720 hours, 30 days, 6 iso weeks, 2 months, 2 q, 1 y
			array(
				'start'      => '2017-09-24T11:00:00Z',
				'end'        => '2017-10-24T10:59:59Z',
				'week_start' => 1,
				'intervals'  => array(
					'hour'    => 30 * 24,
					'day'     => 30, // Sept has 30 days.
					'week'    => 6,
					'month'   => 2,
					'quarter' => 2,
					'year'    => 1,
				),
			),
			// 1 month interval spanning 2 months and 2 quarters, 2 years -> 744 hours, 30 days, 5 iso weeks, 2 months, 2 quarters, 2 years
			array(
				'start'      => '2017-12-24T11:00:00Z',
				'end'        => '2018-01-24T10:59:59Z',
				'week_start' => 1,
				'intervals'  => array(
					'hour'    => 31 * 24,
					'day'     => 31, // Dec has 31 days.
					'week'    => 6,
					'month'   => 2,
					'quarter' => 2,
					'year'    => 2,
				),
			),
			// 3 months interval spanning 1 quarter, 1 year -> 2208 hours, 92 days, 14 iso weeks, 3 months, 1 quarters, 1 years
			array(
				'start'      => '2017-10-01T00:00:00Z',
				'end'        => '2017-12-31T23:59:59Z',
				'week_start' => 1,
				'intervals'  => array(
					'hour'    => 92 * 24, // 92 days
					'day'     => 92,
					'week'    => 14,
					'month'   => 3,
					'quarter' => 1,
					'year'    => 1,
				),
			),
			// 3 months + 1 day interval spanning 2 quarters, 1 year -> 2208 hours, 92 days, 14 iso weeks, 3 months, 2 quarters, 1 years
			array(
				'start'      => '2017-09-30T00:00:00Z',
				'end'        => '2017-12-30T23:59:59Z',
				'week_start' => 1,
				'intervals'  => array(
					'hour'    => 92 * 24, // 92 days
					'day'     => 92,
					'week'    => 14,
					'month'   => 4,
					'quarter' => 2,
					'year'    => 1,
				),
			),
			// 3 months + 1 day interval spanning 2 quarters, 2 years -> 2232 hours, 93 days, 14 iso weeks, 3 months, 2 quarters, 2 years
			array(
				'start'      => '2017-10-31T00:00:00Z',
				'end'        => '2018-01-31T23:59:59Z',
				'week_start' => 1,
				'intervals'  => array(
					'hour'    => 93 * 24, // 93 days
					'day'     => 93,      // Jan 31d + Dec 31d + Nov 30d + Oct 1d = 93d.
					'week'    => 14,
					'month'   => 4,
					'quarter' => 2,
					'year'    => 2,
				),
			),
			// 9 months + 1 day interval spanning 2 quarters, 2 years.
			array(
				'start'      => '2017-04-01T00:00:00Z',
				'end'        => '2018-01-01T00:00:00Z',
				'week_start' => 1,
				'intervals'  => array(
					'month'   => 9 + 1,
					'quarter' => 3 + 1,
					'year'    => 2,
				),
			),
			// 9 months + 1 day interval spanning 2 quarters, 2 years.
			array(
				'start'      => '2015-04-01T00:00:00Z',
				'end'        => '2018-01-01T00:00:00Z',
				'week_start' => 1,
				'intervals'  => array(
					'day'     => 1007,            // This includes leap year in 2016.
					'month'   => 9 + 12 + 12 + 1, // Rest of 2015 + 2016 + 2017 + 1 second in 2018.
					'quarter' => 3 + 4 + 4 + 1,   // Rest of 2015 + 2016 + 2017 + 1 second in 2018.
					'year'    => 4,
				),
			),
		);

		foreach ( $test_settings as $setting ) {
			update_option( 'start_of_week', $setting['week_start'] );
			foreach ( $setting['intervals'] as $interval => $exp_value ) {
				$this->assertEquals( $exp_value, WC_Admin_Reports_Interval::intervals_between( $setting['start'], $setting['end'], $interval ), "FDoW: {$setting['week_start']}; Start: {$setting['start']}; End: {$setting['end']}; Interval: {$interval}" );
			}
		}
	}

	/**
	 * Test function that returns beginning of next hour.
	 */
	public function test_next_hour_start() {
		$settings = array(
			'2017-12-30T00:00:00Z' => array(
				0 => '2017-12-30T01:00:00Z',
				1 => '2017-12-29T23:59:59Z',
			),
			'2017-12-30T10:00:00Z' => array(
				0 => '2017-12-30T11:00:00Z',
				1 => '2017-12-30T09:59:59Z',
			),
		);
		foreach ( $settings as $datetime_s => $setting ) {
			$datetime = new DateTime( $datetime_s );
			foreach ( $setting as $reversed => $exp_value ) {
				$result_dt = WC_Admin_Reports_Interval::next_hour_start( $datetime, $reversed );
				$this->assertEquals( $exp_value, $result_dt->format( WC_Admin_Reports_Interval::$iso_datetime_format ), __FUNCTION__ . ": DT: $datetime_s; R: $reversed" );
			}
		}

		update_option( 'gmt_offset', 2 ); // Not using timezone name here, as it could cause problems with DST.
		$settings = array(
			'2017-12-30T00:00:00+03:45' => array( // 29th, 20:15 UTC, which is 22:15+02
				0 => '2017-12-29 23:00:00',
				1 => '2017-12-29 21:59:59',
			),
			'2017-12-31T23:45:00+02'    => array( // Same as local time.
				0 => '2018-01-01 00:00:00',
				1 => '2017-12-31 22:59:59',
			),
			'2017-12-31T23:45:00+03'    => array( // 20:45 UTC, which is 22:45+02
				0 => '2017-12-31 23:00:00',
				1 => '2017-12-31 21:59:59',
			),
			'2017-12-31T23:45:00+01'    => array( // 22:45 UTC, which is 00:45+02
				0 => '2018-01-01 01:00:00',
				1 => '2017-12-31 23:59:59',
			),
		);
		foreach ( $settings as $datetime_s => $setting ) {
			$datetime = new DateTime( $datetime_s );
			foreach ( $setting as $reversed => $exp_value ) {
				$result_dt = WC_Admin_Reports_Interval::next_hour_start( $datetime, $reversed );
				$this->assertEquals( $exp_value, $result_dt->format( WC_Admin_Reports_Interval::$sql_datetime_format ), __FUNCTION__ . ": DT: $datetime_s; R: $reversed" );
			}
		}
	}

	/**
	 * Test function that returns beginning of next day.
	 */
	public function test_next_day_start() {
		$settings = array(
			'2017-12-30T00:00:00Z' => array(
				0 => '2017-12-31T00:00:00Z',
				1 => '2017-12-29T23:59:59Z',
			),
			'2017-12-30T10:00:00Z' => array(
				0 => '2017-12-31T00:00:00Z',
				1 => '2017-12-29T23:59:59Z',
			),
		);
		foreach ( $settings as $datetime_s => $setting ) {
			$datetime = new DateTime( $datetime_s );
			foreach ( $setting as $reversed => $exp_value ) {
				$result_dt = WC_Admin_Reports_Interval::next_day_start( $datetime, $reversed );
				$this->assertEquals( $exp_value, $result_dt->format( WC_Admin_Reports_Interval::$iso_datetime_format ), __FUNCTION__ . ": DT: $datetime_s; R: $reversed" );
			}
		}

		update_option( 'timezone_string', 'Europe/Berlin' );
		$settings = array(
			'2017-12-30T00:00:00+03' => array( // 29th 21:00 UTC, which is 22:00 or 23:00 Berlin time.
				0 => '2017-12-30 00:00:00',
				1 => '2017-12-28 23:59:59',
			),
			'2017-12-30T10:23:46+05' => array( // 30th 5:23 UTC, thus 6:23 or 7:23 Berlin time
				0 => '2017-12-31 00:00:00',
				1 => '2017-12-29 23:59:59',
			),
		);
		foreach ( $settings as $datetime_s => $setting ) {
			$datetime = new DateTime( $datetime_s );
			foreach ( $setting as $reversed => $exp_value ) {
				$result_dt = WC_Admin_Reports_Interval::next_day_start( $datetime, $reversed );
				$this->assertEquals( $exp_value, $result_dt->format( WC_Admin_Reports_Interval::$sql_datetime_format ), __FUNCTION__ . ": DT: $datetime_s; R: $reversed" );
			}
		}
	}

	/**
	 * Test function that returns beginning of next week, for weeks starting on Monday.
	 */
	public function test_next_week_start_ISO_week() {
		update_option( 'start_of_week', 1 );
		$settings = array(
			'2017-12-30T00:00:00Z' => array(
				0 => '2018-01-01T00:00:00Z',
				1 => '2017-12-24T23:59:59Z',
			),
			'2017-12-30T10:00:00Z' => array(
				0 => '2018-01-01T00:00:00Z',
				1 => '2017-12-24T23:59:59Z',
			),
			'2010-12-25T10:00:00Z' => array(
				0 => '2010-12-27T00:00:00Z',
				1 => '2010-12-19T23:59:59Z',
			),
			'2010-12-26T10:00:00Z' => array(
				0 => '2010-12-27T00:00:00Z',
				1 => '2010-12-19T23:59:59Z',
			),
			'2010-12-27T00:00:00Z' => array(
				0 => '2011-01-03T00:00:00Z',
				1 => '2010-12-26T23:59:59Z',
			),
			'2010-12-31T00:00:00Z' => array(
				0 => '2011-01-03T00:00:00Z',
				1 => '2010-12-26T23:59:59Z',
			),
			'2011-01-01T00:00:00Z' => array(
				0 => '2011-01-03T00:00:00Z',
				1 => '2010-12-26T23:59:59Z',
			),
			'2011-01-03T00:00:00Z' => array(
				0 => '2011-01-10T00:00:00Z',
				1 => '2011-01-02T23:59:59Z',
			),
		);
		foreach ( $settings as $datetime_s => $setting ) {
			$datetime = new DateTime( $datetime_s );
			foreach ( $setting as $reversed => $exp_value ) {
				$result_dt = WC_Admin_Reports_Interval::next_week_start( $datetime, $reversed );
				$this->assertEquals( $exp_value, $result_dt->format( WC_Admin_Reports_Interval::$iso_datetime_format ), __FUNCTION__ . ": DT: $datetime_s; R: $reversed" );
			}
		}

		update_option( 'timezone_string', 'Europe/Berlin' );
		$settings = array(
			'2017-12-30T00:00:00+05' => array(
				0 => '2018-01-01 00:00:00',
				1 => '2017-12-24 23:59:59',
			),
			'2018-01-01T01:00:00+10' => array( // Dec 31st 2017 15:00 UTC, so 16:00 or 17:00 in Berlin.
				0 => '2018-01-01 00:00:00',
				1 => '2017-12-24 23:59:59',
			),
			'2010-12-25T10:00:00+02' => array(
				0 => '2010-12-27 00:00:00',
				1 => '2010-12-19 23:59:59',
			),
		);
		foreach ( $settings as $datetime_s => $setting ) {
			$datetime = new DateTime( $datetime_s );
			foreach ( $setting as $reversed => $exp_value ) {
				$result_dt = WC_Admin_Reports_Interval::next_week_start( $datetime, $reversed );
				$this->assertEquals( $exp_value, $result_dt->format( WC_Admin_Reports_Interval::$sql_datetime_format ), __FUNCTION__ . ": DT: $datetime_s; R: $reversed" );
			}
		}
	}

	/**
	 * Test function that returns beginning of next week, for weeks starting on Sunday.
	 */
	public function test_next_week_start_Sunday_based_week() {
		update_option( 'start_of_week', 7 );
		$settings = array(
			'2010-12-25T10:00:00Z' => array(
				0 => '2010-12-26T00:00:00Z',
				1 => '2010-12-18T23:59:59Z',
			),
			'2010-12-26T10:00:00Z' => array(
				0 => '2011-01-01T00:00:00Z',
				1 => '2010-12-25T23:59:59Z',
			),
			'2011-01-01T00:00:00Z' => array(
				0 => '2011-01-02T00:00:00Z',
				1 => '2010-12-31T23:59:59Z',
			),
			'2011-01-02T00:00:00Z' => array(
				0 => '2011-01-09T00:00:00Z',
				1 => '2011-01-01T23:59:59Z',
			),
		);
		foreach ( $settings as $datetime_s => $setting ) {
			$datetime = new DateTime( $datetime_s );
			foreach ( $setting as $reversed => $exp_value ) {
				$result_dt = WC_Admin_Reports_Interval::next_week_start( $datetime, $reversed );
				$this->assertEquals( $exp_value, $result_dt->format( WC_Admin_Reports_Interval::$iso_datetime_format ), __FUNCTION__ . ": DT: $datetime_s; R: $reversed" );
			}
		}

		update_option( 'timezone_string', 'Europe/Berlin' );
		$settings = array(
			'2010-12-26T01:00:00+10' => array(
				0 => '2010-12-26 00:00:00',
				1 => '2010-12-18 23:59:59',
			),
			'2010-12-26T11:00:00+10' => array(
				0 => '2011-01-01 00:00:00',
				1 => '2010-12-25 23:59:59',
			),
		);
		foreach ( $settings as $datetime_s => $setting ) {
			$datetime = new DateTime( $datetime_s );
			foreach ( $setting as $reversed => $exp_value ) {
				$result_dt = WC_Admin_Reports_Interval::next_week_start( $datetime, $reversed );
				$this->assertEquals( $exp_value, $result_dt->format( WC_Admin_Reports_Interval::$sql_datetime_format ), __FUNCTION__ . ": DT: $datetime_s; R: $reversed" );
			}
		}
	}

	/**
	 * Test function that returns beginning of next month.
	 */
	public function test_next_month_start() {
		$settings = array(
			'2017-12-30T00:00:00Z' => array(
				0 => '2018-01-01T00:00:00Z',
				1 => '2017-11-30T23:59:59Z',
			),
			// Leap year reversed test.
			'2016-03-05T10:00:00Z' => array(
				0 => '2016-04-01T00:00:00Z',
				1 => '2016-02-29T23:59:59Z',
			),
		);
		foreach ( $settings as $datetime_s => $setting ) {
			$datetime = new DateTime( $datetime_s );
			foreach ( $setting as $reversed => $exp_value ) {
				$result_dt = WC_Admin_Reports_Interval::next_month_start( $datetime, $reversed );
				$this->assertEquals( $exp_value, $result_dt->format( WC_Admin_Reports_Interval::$iso_datetime_format ), __FUNCTION__ . ": DT: $datetime_s; R: $reversed" );
			}
		}

		update_option( 'timezone_string', 'Europe/Berlin' );
		$settings = array(
			'2018-01-01T02:00:00+10' => array( // Dec in Berlin.
				0 => '2018-01-01 00:00:00',
				1 => '2017-11-30 23:59:59',
			),
			'2017-12-30T02:00:00+10' => array(
				0 => '2018-01-01 00:00:00',
				1 => '2017-11-30 23:59:59',
			),
			// Leap year reversed test.
			'2016-03-05T10:00:00+04' => array(
				0 => '2016-04-01 00:00:00',
				1 => '2016-02-29 23:59:59',
			),
		);
		foreach ( $settings as $datetime_s => $setting ) {
			$datetime = new DateTime( $datetime_s );
			foreach ( $setting as $reversed => $exp_value ) {
				$result_dt = WC_Admin_Reports_Interval::next_month_start( $datetime, $reversed );
				$this->assertEquals( $exp_value, $result_dt->format( WC_Admin_Reports_Interval::$sql_datetime_format ), __FUNCTION__ . ": DT: $datetime_s; R: $reversed" );
			}
		}
	}

	/**
	 * Test function that returns beginning of next quarter.
	 */
	public function test_next_quarter_start() {
		$settings = array(
			'2017-12-31T00:00:00Z' => array(
				0 => '2018-01-01T00:00:00Z',
				1 => '2017-09-30T23:59:59Z',
			),
			'2018-01-01T10:00:00Z' => array(
				0 => '2018-04-01T00:00:00Z',
				1 => '2017-12-31T23:59:59Z',
			),
			'2018-02-14T10:00:00Z' => array(
				0 => '2018-04-01T00:00:00Z',
				1 => '2017-12-31T23:59:59Z',
			),
			'2018-04-14T10:00:00Z' => array(
				0 => '2018-07-01T00:00:00Z',
				1 => '2018-03-31T23:59:59Z',
			),
			'2018-07-14T10:00:00Z' => array(
				0 => '2018-10-01T00:00:00Z',
				1 => '2018-06-30T23:59:59Z',
			),
		);
		foreach ( $settings as $datetime_s => $setting ) {
			$datetime = new DateTime( $datetime_s );
			foreach ( $setting as $reversed => $exp_value ) {
				$result_dt = WC_Admin_Reports_Interval::next_quarter_start( $datetime, $reversed );
				$this->assertEquals( $exp_value, $result_dt->format( WC_Admin_Reports_Interval::$iso_datetime_format ), __FUNCTION__ . ": DT: $datetime_s; R: $reversed" );
			}
		}

		update_option( 'timezone_string', 'Europe/Berlin' );
		$settings = array(
			'2018-01-01T02:00:00+10' => array(
				0 => '2018-01-01 00:00:00',
				1 => '2017-09-30 23:59:59',
			),
			'2017-12-31T22:00:00-08' => array(
				0 => '2018-04-01 00:00:00',
				1 => '2017-12-31 23:59:59',
			),
		);
		foreach ( $settings as $datetime_s => $setting ) {
			$datetime = new DateTime( $datetime_s );
			foreach ( $setting as $reversed => $exp_value ) {
				$result_dt = WC_Admin_Reports_Interval::next_quarter_start( $datetime, $reversed );
				$this->assertEquals( $exp_value, $result_dt->format( WC_Admin_Reports_Interval::$sql_datetime_format ), __FUNCTION__ . ": DT: $datetime_s; R: $reversed" );
			}
		}
	}

	/**
	 * Test function that returns beginning of next year.
	 */
	public function test_next_year_start() {
		$settings = array(
			'2017-12-31T23:59:59Z' => array(
				0 => '2018-01-01T00:00:00Z',
				1 => '2016-12-31T23:59:59Z',
			),
			'2017-01-01T00:00:00Z' => array(
				0 => '2018-01-01T00:00:00Z',
				1 => '2016-12-31T23:59:59Z',
			),
			'2017-04-23T14:53:00Z' => array(
				0 => '2018-01-01T00:00:00Z',
				1 => '2016-12-31T23:59:59Z',
			),
		);
		foreach ( $settings as $datetime_s => $setting ) {
			$datetime = new DateTime( $datetime_s );
			foreach ( $setting as $reversed => $exp_value ) {
				$result_dt = WC_Admin_Reports_Interval::next_year_start( $datetime, $reversed );
				$this->assertEquals( $exp_value, $result_dt->format( WC_Admin_Reports_Interval::$iso_datetime_format ), __FUNCTION__ . ": DT: $datetime_s; R: $reversed" );
			}
		}

		update_option( 'timezone_string', 'Europe/Berlin' );
		$settings = array(
			'2018-01-01T02:00:00+06' => array( // 2017 in Berlin.
				0 => '2018-01-01 00:00:00',
				1 => '2016-12-31 23:59:59',
			),
			'2017-12-31T22:00:00-10' => array( // 2018 in Berlin.
				0 => '2019-01-01 00:00:00',
				1 => '2017-12-31 23:59:59',
			),
		);
		foreach ( $settings as $datetime_s => $setting ) {
			$datetime = new DateTime( $datetime_s );
			foreach ( $setting as $reversed => $exp_value ) {
				$result_dt = WC_Admin_Reports_Interval::next_year_start( $datetime, $reversed );
				$this->assertEquals( $exp_value, $result_dt->format( WC_Admin_Reports_Interval::$sql_datetime_format ), __FUNCTION__ . ": DT: $datetime_s; R: $reversed" );
			}
		}
	}

}