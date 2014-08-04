<?php
/* Cronjob for fetching all EPS banks (through the Scheme Operator's XML interface) and storing them locally. This version uses a database, but you can also write to a text file, to APC or whatever you like.
 * Reasons for storing the bank information at all:
 * 	1. If you want to display the bank logos (or nicer names instead of the 'official' bank names), you need to store the information about them anyway.
 *  2. Doesn't require to fetch the list of banks from the (sometimes slow) Scheme Operator upon each payment intialization
 *  
 * Run this cronjob periodically (e.g. once per day).
 * 
 * Setup for database usage:
 * 1. $db needs to be the connection to the database: $db = new mysqli($host, $username, $passwd, $dbname);
 * 2. Create a table like this: CREATE TABLE banks (bezeichnung VARCHAR(255) PRIMARY KEY, bic VARCHAR(255), land VARCHAR(255), epsUrl VARCHAR(255));
 * 3. Enter the actual name of your table here:
 */
$table = 'banks';

require_once('eps/src/autoloader.php');
use at\externet\eps_bank_transfer;

$soCommunicator = new eps_bank_transfer\SoCommunicator();
$banks = $soCommunicator->TryGetBanksArray(); // associative array (keys: bezeichnung): [bic], [bezeichnung], [land], [epsUrl]
foreach ($banks as $bank)
{
	// Check if the bank is already in the database:
	$sql = "SELECT bezeichnung, bic, land, epsUrl FROM $table WHERE bezeichnung='".$db->real_escape_string($bank['bezeichnung'])."'";
	$result = $db->query($sql);
	if ($result->num_rows===0) // i.e. bank is not in the database
	{
		$sql = "INSERT INTO $table SET	bezeichnung='".$db->real_escape_string($bank['bezeichnung'])."',
										bic='".$db->real_escape_string($bank['bic'])."',
										land='".$db->real_escape_string($bank['land'])."',
										epsUrl='".$db->real_escape_string($bank['epsUrl'])."'";
		$ok = $db->query($sql);
		if ($ok)
		{
			// TODO: Send email notice
		}
		else
		{
			// TODO: Send email notice
		}
	}
	else
	{
		$row = $result->fetch_assoc();
		if ($row['bic']!=$bank['bic'] or $row['land']!=$bank['land'] or $row['epsUrl']!=$bank['epsUrl']) // i.e. bank is in the database, but with different bic/land/epsUrl
		{
			$sql = "UPDATE $table SET	bic='".$db->real_escape_string($bank['bic'])."',
										land='".$db->real_escape_string($bank['land'])."',
										epsUrl='".$db->real_escape_string($bank['epsUrl'])."'
								  WHERE bezeichnung='".$db->real_escape_string($bank['bezeichnung'])."'";
			$ok = $db->query($sql);
			if ($ok)
			{
				// TODO: Send email notice
			}
			else
			{
				// TODO: Send email notice
			}
		}
	}
}
// Check if any bank in the database is not active anymore:
$sql = "SELECT bezeichnung FROM $table";
$result = $db->query($sql);
if ($result->num_rows != count($banks))
{
	while ($row=$result->fetch_assoc())
	{
		if ( ! isset($banks[$row['bezeichnung']]))
		{
			$sql = "DELETE FROM $table WHERE bezeichnung='".$db->real_escape_string($bank['bezeichnung'])."'";
			$ok = $db->query($sql);
			if ( ! $ok)
			{
				// TODO: Send email notice
			}
		}
	}
}
?>
