CREATE TABLE `akun` (
                        `id_akun` int(2) NOT NULL,
                        `nama` varchar(30) NOT NULL,
                        `email` varchar(30) NOT NULL,
                        `password` varchar(10) NOT NULL,
                        `nohp` int(15) NOT NULL,
                        `nik` int(20) NOT NULL
);

INSERT INTO `akun` (`id_akun`, `nama`, `email`, `password`, `nohp`, `nik`) VALUES
                                                                               (1, 'Delia', 'delia@gmail.com', 'delia', '081233212345', '1234567890'),
                                                                               (2, 'Sari', 'sari@gmail.com', 'sari', '081256678900', '1234543212'),
                                                                               (3, 'Admin', 'admin@gmail.com', 'admin', '081234321211', '1231345621');

ALTER TABLE `akun` ADD PRIMARY KEY (`id_akun`);
ALTER TABLE `akun` MODIFY `id_akun` int(2) NOT NULL AUTO_INCREMENT;



CREATE TABLE `masjid` (
                          `id_masjid` int(2) NOT NULL,
                          `img` text NOT NULL,
                          `nama_masjid` varchar(30) NOT NULL,
                          `tahun_berdiri` int(4) NOT NULL,
                          `takmir` varchar(30) NOT NULL,
                          `deskripsi` varchar(100) NOT NULL
);

INSERT INTO `masjid` (`id_masjid`, `img`, `nama_masjid`, `tahun_berdiri`, `takmir`, `deskripsi`) VALUES
                                                                                                     (1, 'http://localhost/donasii/images/alhuda.jpg', 'Masjid Al-Huda', '2000', 'Sumanto', 'Masjid ini sangat perlu direhab pada bagian atap, plafon, dan sekeliling masjid.'),
                                                                                                     (2, 'http://localhost/donasii/images/alikhlas.jpg', 'Masjid Al-Ikhlas', '2003', 'Ahmad', 'Masjid perlu dana untuk melanjutkan pembangunan demi kenyamanan dalam beribadah.'),
                                                                                                     (3, 'http://localhost/donasii/images/almuttaqin.jpg', 'Masjid Al-Muttaqin', '2015', 'Beni', 'Masjid ini mulai rusak akibat hujan badai sehingga perlu direhab 100%.');

ALTER TABLE `masjid` ADD PRIMARY KEY (`id_masjid`);
ALTER TABLE `masjid` MODIFY `id_masjid` int(5) NOT NULL AUTO_INCREMENT;



CREATE TABLE `histori` (
                           `id_histori` int(2) NOT NULL,
                           `id_akun` int(2) NOT NULL,
                           `id_masjid` int(2) NOT NULL,
                           `username` varchar(30) NOT NULL,
                           `nominal` int(10) NOT NULL,
                           `note` varchar(50) NOT NULL
);

INSERT INTO `histori` (`id_histori`, `id_akun`, `id_masjid`, `username`, `nominal`, `note`) VALUES
                                                                                                (1, 1, 2, 'Hamba Allah', 100.000, 'Semoga membantu'),
                                                                                                (1, 1, 1, 'Anonymous', 200.000, 'Semoga Berkah');

ALTER TABLE `histori` ADD PRIMARY KEY (`id_histori`);
ALTER TABLE `histori` MODIFY `id_histori` int(2) NOT NULL AUTO_INCREMENT;
ALTER TABLE `histori` ADD CONSTRAINT `admin` FOREIGN KEY (`id_akun`) REFERENCES `akun` (`id_akun`);
ALTER TABLE `histori` ADD CONSTRAINT `masjid` FOREIGN KEY (`id_masjid`) REFERENCES `masjid` (`id_masjid`);
