INSERT IGNORE INTO `fb_dashboards` (`dashboard_id`, `dashboard_name`, `dashboard_comment`, `dashboard_priority`, `params`, `created_at`, `updated_at`) VALUES
	(_binary 0x272379D8835144B6AD8D73A0ABCB7F9C, 'Main dashboard', NULL, 0, '[]', '2020-05-28 10:43:58', '2020-05-28 10:43:58'),
	(_binary 0xAB369E71ADA64D1AA5A8B6EE5CD58296, 'First floor', NULL, 0, '[]', '2020-05-28 11:03:50', '2020-05-28 11:03:50');

INSERT IGNORE INTO `fb_dashboards_groups` (`group_id`, `dashboard_id`, `group_name`, `group_comment`, `group_priority`, `params`, `created_at`, `updated_at`) VALUES
	(_binary 0x89F4A14F7F78421699B8584AB9229F1C, _binary 0xAB369E71ADA64D1AA5A8B6EE5CD58296, 'Sleeping room', NULL, 0, '[]', '2020-05-28 11:04:40', '2020-05-28 12:29:32'),
	(_binary 0xC74A16B167F44FFD812A9E5EC4BD5263, _binary 0x272379D8835144B6AD8D73A0ABCB7F9C, 'House heaters', NULL, 0, '[]', '2020-05-28 11:03:33', '2020-05-28 11:03:33'),
	(_binary 0xD721529EDEC647C88035A3484070142B, _binary 0x272379D8835144B6AD8D73A0ABCB7F9C, 'Main lights', NULL, 0, '[]', '2020-05-28 11:02:59', '2020-05-28 11:02:59');

INSERT IGNORE INTO `fb_widgets` (`widget_id`, `widget_name`, `params`, `created_at`, `updated_at`, `widget_type`) VALUES
	(_binary 0x155534434564454DAF040DFEEF08AA96, 'Room temperature', '[]', '2020-05-28 12:29:32', '2020-05-28 12:29:32', 'analog_sensor'),
	(_binary 0x1D60090154E743EE8F5DA9E22663DDD7, 'Ambient light', '[]', '2020-05-28 12:27:47', '2020-05-28 12:27:47', 'analog_actuator'),
	(_binary 0x5626E7A1C42C4A319B5D848E3CF0E82A, 'TV light', '[]', '2020-05-28 12:07:27', '2020-05-28 12:07:27', 'digital_actuator'),
	(_binary 0x9A91473298DC47F6BFD19D81CA9F8CB6, 'Main light', '[]', '2020-05-28 11:35:44', '2020-05-28 11:35:44', 'digital_actuator');

INSERT IGNORE INTO `fb_widgets_data_sources` (`data_source_id`, `widget_id`, `params`, `created_at`, `updated_at`, `data_source_type`) VALUES
	(_binary 0x32DD50E44B664DEA9BC5E835F8543DC4, _binary 0x1D60090154E743EE8F5DA9E22663DDD7, '[]', '2020-05-28 12:27:47', '2020-05-28 12:27:47', 'channel_property'),
	(_binary 0x764937A78565472E8E12FE97CD55A377, _binary 0x155534434564454DAF040DFEEF08AA96, '[]', '2020-05-28 12:29:32', '2020-05-28 12:29:32', 'channel_property'),
	(_binary 0xCD96AA91A0A44A6C9D682E0754A0A56D, _binary 0x9A91473298DC47F6BFD19D81CA9F8CB6, '[]', '2020-05-28 11:35:44', '2020-05-28 11:35:44', 'channel_property'),
	(_binary 0xFFE067C88C024C2CB8DD05256A121215, _binary 0x5626E7A1C42C4A319B5D848E3CF0E82A, '[]', '2020-05-28 12:07:27', '2020-05-28 12:07:27', 'channel_property');

INSERT IGNORE INTO `fb_widgets_data_sources_channels_properties` (`data_source_id`, `data_source_channel`, `data_source_property`) VALUES
	(_binary 0x32DD50E44B664DEA9BC5E835F8543DC4, 'channel-one', 'action-property'),
	(_binary 0x764937A78565472E8E12FE97CD55A377, 'channel-one', 'action-property'),
	(_binary 0xCD96AA91A0A44A6C9D682E0754A0A56D, 'channel-one', 'action-property'),
	(_binary 0xFFE067C88C024C2CB8DD05256A121215, 'channel-one', 'action-property');

INSERT IGNORE INTO `fb_widgets_display` (`display_id`, `widget_id`, `params`, `created_at`, `updated_at`, `display_type`) VALUES
	(_binary 0x2EA64D790D7D43D9BE3B51F9ADE849FC, _binary 0x5626E7A1C42C4A319B5D848E3CF0E82A, '[]', '2020-05-28 12:07:27', '2020-05-28 12:07:27', 'button'),
	(_binary 0x467E6D4D3545481BB61353BE7E9AA641, _binary 0x155534434564454DAF040DFEEF08AA96, '{"precision": 0, "stepValue": 0.1, "maximumValue": 40, "minimumValue": 5}', '2020-05-28 12:29:32', '2020-05-28 12:29:32', 'chart_graph'),
	(_binary 0x56A01A188DA14368824E3E1B2E28BDF1, _binary 0x1D60090154E743EE8F5DA9E22663DDD7, '{"precision": 0, "stepValue": 0.1, "maximumValue": 40, "minimumValue": 5}', '2020-05-28 12:27:47', '2020-05-28 12:27:47', 'slider'),
	(_binary 0xFD47E9AF1120458F8F30ADEBAC933406, _binary 0x9A91473298DC47F6BFD19D81CA9F8CB6, '[]', '2020-05-28 11:35:44', '2020-05-28 11:35:44', 'button');

INSERT IGNORE INTO `fb_widgets_groups` (`group_id`, `widget_id`) VALUES
	(_binary 0x89F4A14F7F78421699B8584AB9229F1C, _binary 0x155534434564454DAF040DFEEF08AA96),
	(_binary 0x89F4A14F7F78421699B8584AB9229F1C, _binary 0x1D60090154E743EE8F5DA9E22663DDD7),
	(_binary 0x89F4A14F7F78421699B8584AB9229F1C, _binary 0x5626E7A1C42C4A319B5D848E3CF0E82A),
	(_binary 0x89F4A14F7F78421699B8584AB9229F1C, _binary 0x9A91473298DC47F6BFD19D81CA9F8CB6);
