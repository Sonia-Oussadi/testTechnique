
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gestion_de_notes`
--
-- À mettre en commentaire pour installation sur pw
CREATE DATABASE IF NOT EXISTS `gestion_de_notes` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `gestion_de_notes`;

-- --------------------------------------------------------

--
-- Table structure for table `matiere`
--
DROP TABLE IF EXISTS `matiere`;
CREATE TABLE `matiere` (
  `idm` int(11) NOT NULL,
  `intitule` varchar(30) NOT NULL,
  `coefficient` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--
-- Dumping data for table `matiere`
--
INSERT INTO `matiere` (`idm`, `intitule`,`coefficient`) VALUES
(1, 'Anglais',3),
(2, 'Français',3),
(3, 'Mathematiques',6);

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

DROP TABLE IF EXISTS `note`;
CREATE TABLE `note` (
  `idn` int(11) NOT NULL,
  `idm` int(11) NOT NULL,
  `ide` int(11) NOT NULL,
  `valeur`int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `etudiant`
--

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE `etudiant` (
  `ide` int(11) NOT NULL,
  `nom` varchar(30) DEFAULT NULL,
  `prenom` varchar(30) DEFAULT NULL,
  `numero` int(11) UNSIGNED NOT NULL,
  `moyenne` float UNSIGNED NOT NULL,
  `statut` enum('en-attente','remplis') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `etudiant`
--

INSERT INTO `etudiant` (`ide`, `nom`, `prenom`, `numero`,`statut`,`moyenne`) VALUES
(1, 'etudiant1', 'xxx', 213151,'en-attente',0.0),
(2, 'etudiant2', 'yyy', 213152,'en-attente',0.0),
(3, 'etudiant3', 'zzz', 213153,'en-attente',0.0),
(4, 'etudiant4', 'aaa', 213154,'en-attente',0.0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `matiere`
--
ALTER TABLE `matiere`
  ADD PRIMARY KEY (`idm`);

--
-- Indexes for table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`idn`),
  ADD KEY `idm` (`idm`) USING BTREE,
  ADD KEY `ide` (`ide`);

--


--
-- Indexes for table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`ide`),
  ADD UNIQUE KEY `numero` (`numero`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `matiere`
--
ALTER TABLE `matiere`
  MODIFY `idm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `note`
--
ALTER TABLE `note`
  MODIFY `idn` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `etudiant`
--
ALTER TABLE `etudiant`
  MODIFY `ide` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `note_ibfk_1` FOREIGN KEY (`ide`) REFERENCES `etudiant` (`ide`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `note_ibfk_2` FOREIGN KEY (`idm`) REFERENCES `matiere` (`idm`) ON DELETE CASCADE ON UPDATE CASCADE;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
