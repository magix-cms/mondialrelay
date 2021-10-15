CREATE TABLE IF NOT EXISTS `mc_mondial_relay` (
  `id_mrelay` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `merchant` varchar(50) NULL,
  `privatekey` varchar(150) NULL,
  PRIMARY KEY (`id_mrelay`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `mc_mondial_relay_transport` (
  `id_mrtr` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_buyer` int(11) UNSIGNED NOT NULL,
  `rel_id` varchar(20) NOT NULL,
  `expedition_num` varchar(50) NOT NULL,
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mrtr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO `mc_admin_access` (`id_role`, `id_module`, `view`, `append`, `edit`, `del`, `action`)
  SELECT 1, m.id_module, 1, 1, 1, 1, 1 FROM mc_module as m WHERE name = 'mondialrelay';