<?php
define("TIMEOUT", 900); //900 detik=15 menit

function openConnection()
{
	try {
		$con = new PDO('mysql:host=localhost;port=3306;dbname=hotel', 'root', '');
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		throw new Exception("0: " . $e->getMessage());
	}
	return $con;
}

//Select
function querySingleValue($con, $sSql, $values)
{
	//return single Value
	try {
		if (!($stmt = $con->prepare($sSql))) {
			throw new Exception("0:  (" . $con->errno . ") " . $con->error);
		} else {
			$paramValues = $values;
			if (strpos($sSql, "?")) {
				for ($i = 0; $i < sizeof($values); $i++) {
					$paramCount = $i + 1;
					$paramValues[$i] = descapeCSV($values[$i]);
					$stmt->bindParam($paramCount, $paramValues[$i]);
				}
			} else if (strpos($sSql, ":1")) {
				for ($i = 0; $i < sizeof($values); $i++) {
					$paramCount = $i + 1;
					$paramValues[$i] = descapeCSV($values[$i]);
					$stmt->bindParam(":" . $paramCount, $paramValues[$i]);
				}
			} else {
				foreach ($paramValues as $key => $value)
					$stmt->bindValue(':' . $key, $value);
			}

			$stmt->execute();
			if ($row = $stmt->fetch(PDO::FETCH_BOTH)) {
				return $row[0];
			} else {
				return null;
			}
		}
	} catch (PDOException $e) {
		throw new Exception("0: " . $e->getMessage());
	}
}

function queryArrayValue($con, $sSql, $values)
{
	//return array values
	try {
		if (!($stmt = $con->prepare($sSql))) {
			throw new Exception("0:  (" . $con->errno . ") " . $con->error);
		} else {
			$paramValues = $values;
			if (strpos($sSql, "?")) {
				for ($i = 0; $i < sizeof($values); $i++) {
					$paramCount = $i + 1;
					$paramValues[$i] = descapeCSV($values[$i]);
					$stmt->bindParam($paramCount, $paramValues[$i]);
				}
			} else if (strpos($sSql, ":1")) {
				for ($i = 0; $i < sizeof($values); $i++) {
					$paramCount = $i + 1;
					$paramValues[$i] = descapeCSV($values[$i]);
					$stmt->bindParam(":" . $paramCount, $paramValues[$i]);
				}
			} else {
				foreach ($paramValues as $key => $value)
					$stmt->bindValue(':' . $key, $value);
			}

			$stmt->execute();
			if ($row = $stmt->fetch(PDO::FETCH_BOTH)) {
				return $row;
			} else {
				return null;
			}
		}
	} catch (PDOException $e) {
		throw new Exception("0: " . $e->getMessage());
	}
}
function queryArrayValue1($con, $sql, $data)
{
	$stmt = $con->prepare($sql);
	$stmt->execute($data);
	return $stmt->fetch(PDO::FETCH_ASSOC);
}


function queryArrayRowsValue($con, $sSql, $values)
{
	//return array values (1 dimensi)
	$arr = array();
	try {
		if (!($stmt = $con->prepare($sSql))) {
			throw new Exception("0:  (" . $con->errno . ") " . $con->error);
		} else {
			$paramValues = $values;
			if (strpos($sSql, "?")) {
				for ($i = 0; $i < sizeof($values); $i++) {
					$paramCount = $i + 1;
					$paramValues[$i] = descapeCSV($values[$i]);
					$stmt->bindParam($paramCount, $paramValues[$i]);
				}
			} else if (strpos($sSql, ":1")) {
				for ($i = 0; $i < sizeof($values); $i++) {
					$paramCount = $i + 1;
					$paramValues[$i] = descapeCSV($values[$i]);
					$stmt->bindParam(":" . $paramCount, $paramValues[$i]);
				}
			} else {
				foreach ($paramValues as $key => $value)
					$stmt->bindValue(':' . $key, $value);
			}

			$stmt->execute();
			if ($row = $stmt->fetch(PDO::FETCH_BOTH)) {
				do {
					$arr[] = $row[0];
				} while ($row = $stmt->fetch(PDO::FETCH_BOTH));
			}
			return $arr;
		}
	} catch (PDOException $e) {
		throw new Exception("0: " . $e->getMessage());
	}
}

function queryArrayRowsValues($con, $sSql, $values)
{
	//return array values (2 dimensi)
	$arr = array();
	try {
		if (!($stmt = $con->prepare($sSql))) {
			throw new Exception("0:  (" . $con->errno . ") " . $con->error);
		} else {
			$paramValues = $values;
			if (strpos($sSql, "?")) {
				for ($i = 0; $i < sizeof($values); $i++) {
					$paramCount = $i + 1;
					$paramValues[$i] = descapeCSV($values[$i]);
					$stmt->bindParam($paramCount, $paramValues[$i]);
				}
			} else if (strpos($sSql, ":1")) {
				for ($i = 0; $i < sizeof($values); $i++) {
					$paramCount = $i + 1;
					$paramValues[$i] = descapeCSV($values[$i]);
					$stmt->bindParam(":" . $paramCount, $paramValues[$i]);
				}
			} else {

				foreach ($paramValues as $key => $value)
					$stmt->bindValue(':' . $key, $value);
			}

			$stmt->execute();
			if ($row = $stmt->fetch(PDO::FETCH_BOTH)) {
				do {

					$arr[] = $row;
				} while ($row = $stmt->fetch(PDO::FETCH_BOTH));
			}

			return $arr;
		}
	} catch (PDOException $e) {
		throw new Exception("0: " . $e->getMessage());
	}
}

//CRUD
function createRow($con, $sSql, $values)
{
	//return row Affected
	try {
		if (!($stmt = $con->prepare($sSql))) {
			throw new Exception("0:  (" . $con->errno . ") " . $con->error);
		} else {
			$paramValues = $values;
			if (strpos($sSql, "?")) {
				for ($i = 0; $i < sizeof($values); $i++) {
					$paramCount = $i + 1;
					$paramValues[$i] = descapeCSV($values[$i]);
					$stmt->bindParam($paramCount, $paramValues[$i]);
				}
			} else if (strpos($sSql, ":1")) {
				for ($i = 0; $i < sizeof($values); $i++) {
					$paramCount = $i + 1;
					$paramValues[$i] = descapeCSV($values[$i]);
					$stmt->bindParam(":" . $paramCount, $paramValues[$i]);
				}
			} else {
				foreach ($paramValues as $key => $value)
					$stmt->bindValue(':' . $key, $value);
			}
			$stmt->execute();
			return $stmt->rowCount();
		}
	} catch (PDOException $e) {
		throw new Exception("0: " . $e->getMessage());
	}
}

function updateRow($con, $sSql, $values)
{
	//return row Affected
	try {
		if (!($stmt = $con->prepare($sSql))) {
			throw new Exception("0:  (" . $con->errno . ") " . $con->error);
		} else {
			$paramValues = $values;
			if (strpos($sSql, "?")) {
				for ($i = 0; $i < sizeof($values); $i++) {
					$paramCount = $i + 1;
					$paramValues[$i] = descapeCSV($values[$i]);
					$stmt->bindParam($paramCount, $paramValues[$i]);
				}
			} else if (strpos($sSql, ":1")) {
				for ($i = 0; $i < sizeof($values); $i++) {
					$paramCount = $i + 1;
					$paramValues[$i] = descapeCSV($values[$i]);
					$stmt->bindParam(":" . $paramCount, $paramValues[$i]);
				}
			} else {
				foreach ($paramValues as $key => $value)
					$stmt->bindValue(':' . $key, $value);
			}
			$stmt->execute();
			return $stmt->rowCount();
		}
	} catch (PDOException $e) {
		throw new Exception("0: " . $e->getMessage());
	}
}

function deleteRow($con, $sSql, $values)
{
	//return row Affected
	try {
		if (!($stmt = $con->prepare($sSql))) {
			throw new Exception("0:  (" . $con->errno . ") " . $con->error);
		} else {
			$paramValues = $values;
			if (strpos($sSql, "?")) {
				for ($i = 0; $i < sizeof($values); $i++) {
					$paramCount = $i + 1;
					$paramValues[$i] = descapeCSV($values[$i]);
					$stmt->bindParam($paramCount, $paramValues[$i]);
				}
			} else if (strpos($sSql, ":1")) {
				for ($i = 0; $i < sizeof($values); $i++) {
					$paramCount = $i + 1;
					$paramValues[$i] = descapeCSV($values[$i]);
					$stmt->bindParam(":" . $paramCount, $paramValues[$i]);
				}
			} else {
				foreach ($paramValues as $key => $value)
					$stmt->bindValue(':' . $key, $value);
			}
			$stmt->execute();
			return $stmt->rowCount();
		}
	} catch (PDOException $e) {
		throw new Exception("0: " . $e->getMessage());
	}
}

//setting timezone