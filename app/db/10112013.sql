
-- -------------------
-- Create a new column to handle duplicate of pixels in the campaigns
-- ------------------------------------------------

ALTER TABLE `bt_u_campaigns`
ADD COLUMN `allow_duplicate_conversion` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Allow duplicate conversions' AFTER `type`;	