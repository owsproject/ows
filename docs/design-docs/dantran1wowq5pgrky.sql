/*
Navicat MySQL Data Transfer

Source Server         : OWS - acquia.dev [dantran1wowq5pgrky]
Source Server Version : 50524
Source Host           : free-3509.devcloud.hosting.acquia.com:3306
Source Database       : dantran1wowq5pgrky

Target Server Type    : MYSQL
Target Server Version : 50524
File Encoding         : 65001

Date: 2014-08-08 23:23:55
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for actions
-- ----------------------------
DROP TABLE IF EXISTS `actions`;
CREATE TABLE `actions` (
  `aid` varchar(255) NOT NULL DEFAULT '0' COMMENT 'Primary Key: Unique actions ID.',
  `type` varchar(32) NOT NULL DEFAULT '' COMMENT 'The object that that action acts on (node, user, comment, system or custom types.)',
  `callback` varchar(255) NOT NULL DEFAULT '' COMMENT 'The callback function that executes when the action runs.',
  `parameters` longblob NOT NULL COMMENT 'Parameters to be passed to the callback function.',
  `label` varchar(255) NOT NULL DEFAULT '0' COMMENT 'Label of the action.',
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores action information.';

-- ----------------------------
-- Records of actions
-- ----------------------------
INSERT INTO `actions` VALUES ('comment_publish_action', 'comment', 'comment_publish_action', '', 'Publish comment');
INSERT INTO `actions` VALUES ('comment_save_action', 'comment', 'comment_save_action', '', 'Save comment');
INSERT INTO `actions` VALUES ('comment_unpublish_action', 'comment', 'comment_unpublish_action', '', 'Unpublish comment');
INSERT INTO `actions` VALUES ('node_make_sticky_action', 'node', 'node_make_sticky_action', '', 'Make content sticky');
INSERT INTO `actions` VALUES ('node_make_unsticky_action', 'node', 'node_make_unsticky_action', '', 'Make content unsticky');
INSERT INTO `actions` VALUES ('node_promote_action', 'node', 'node_promote_action', '', 'Promote content to front page');
INSERT INTO `actions` VALUES ('node_publish_action', 'node', 'node_publish_action', '', 'Publish content');
INSERT INTO `actions` VALUES ('node_save_action', 'node', 'node_save_action', '', 'Save content');
INSERT INTO `actions` VALUES ('node_unpromote_action', 'node', 'node_unpromote_action', '', 'Remove content from front page');
INSERT INTO `actions` VALUES ('node_unpublish_action', 'node', 'node_unpublish_action', '', 'Unpublish content');
INSERT INTO `actions` VALUES ('system_block_ip_action', 'user', 'system_block_ip_action', '', 'Ban IP address of current user');
INSERT INTO `actions` VALUES ('user_block_user_action', 'user', 'user_block_user_action', '', 'Block current user');

-- ----------------------------
-- Table structure for authmap
-- ----------------------------
DROP TABLE IF EXISTS `authmap`;
CREATE TABLE `authmap` (
  `aid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key: Unique authmap ID.',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT 'User’s users.uid.',
  `authname` varchar(128) NOT NULL DEFAULT '' COMMENT 'Unique authentication name.',
  `module` varchar(128) NOT NULL DEFAULT '' COMMENT 'Module which is controlling the authentication.',
  PRIMARY KEY (`aid`),
  UNIQUE KEY `authname` (`authname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores distributed authentication mapping.';

-- ----------------------------
-- Records of authmap
-- ----------------------------
