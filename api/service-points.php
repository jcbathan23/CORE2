<?php
require_once __DIR__ . '/../db.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

switch ($method) {
	case 'GET':
		if ($id > 0) {
			$stmt = $mysqli->prepare('SELECT * FROM service_points WHERE id = ?');
			$stmt->bind_param('i', $id);
			$stmt->execute();
			$row = $stmt->get_result()->fetch_assoc();
			return $row ? send_json($row) : send_json(['error' => 'Not found'], 404);
		}
		$res = $mysqli->query('SELECT * FROM service_points ORDER BY id ASC');
		$data = [];
		while ($r = $res->fetch_assoc()) $data[] = $r;
		return send_json($data);

	case 'POST':
		$body = read_json_body();
		$errors = validate_point($body);
		if ($errors) return send_json(['errors' => $errors], 422);
		$stmt = $mysqli->prepare('INSERT INTO service_points (name,type,location,services,status,notes) VALUES (?,?,?,?,?,?)');
		$stmt->bind_param('ssssss', $body['name'],$body['type'],$body['location'],$body['services'],$body['status'],$body['notes']);
		if (!$stmt->execute()) return send_json(['error' => 'Insert failed', 'details' => $stmt->error], 500);
		return after_write($stmt->insert_id);

	case 'PUT':
		if ($id <= 0) return send_json(['error' => 'Missing id'], 400);
		$body = read_json_body();
		$errors = validate_point($body);
		if ($errors) return send_json(['errors' => $errors], 422);
		$stmt = $mysqli->prepare('UPDATE service_points SET name=?,type=?,location=?,services=?,status=?,notes=? WHERE id=?');
		$stmt->bind_param('ssssssi', $body['name'],$body['type'],$body['location'],$body['services'],$body['status'],$body['notes'],$id);
		if (!$stmt->execute()) return send_json(['error' => 'Update failed', 'details' => $stmt->error], 500);
		return after_write($id);

	case 'DELETE':
		if ($id <= 0) return send_json(['error' => 'Missing id'], 400);
		$stmt = $mysqli->prepare('DELETE FROM service_points WHERE id = ?');
		$stmt->bind_param('i', $id);
		if (!$stmt->execute()) return send_json(['error' => 'Delete failed', 'details' => $stmt->error], 500);
		return send_json(['success' => true]);

	default:
		return send_json(['error' => 'Method not allowed'], 405);
}

function validate_point(array $b): array {
	$e = [];
	$req = ['name','type','location','services','status'];
	foreach ($req as $f) if (!isset($b[$f]) || trim((string)$b[$f]) === '') $e[$f] = 'Required';
	return $e;
}

function after_write(int $id): void {
	global $mysqli;
	$stmt = $mysqli->prepare('SELECT * FROM service_points WHERE id = ?');
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$row = $stmt->get_result()->fetch_assoc();
	send_json($row, 201);
}

?>


