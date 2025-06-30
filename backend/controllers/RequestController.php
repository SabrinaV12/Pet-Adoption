<?php
require_once __DIR__.'/../repositories/RequestRepository.php';
class RequestController {
  public function getRequestDetails($rid) {
    header('Content-Type: application/json');
    $repo = new RequestRepository();
    $req = $repo->getById($rid);
    if (!$req) { http_response_code(404); echo json_encode(['message'=>'Not found']); exit(); }
    echo json_encode($req);
  }

  public function accept($rid) {
    header('Content-Type: application/json');
    $repo = new RequestRepository();
    $ok = $repo->acceptRequest($rid);
    echo json_encode(['message'=> $ok ? 'Request accepted.' : 'Failed to accept']);
  }

  public function deny($rid) {
    header('Content-Type: application/json');
    $repo = new RequestRepository();
    $ok = $repo->denyRequest($rid);
    echo json_encode(['message'=> $ok ? 'Request denied.' : 'Failed to deny']);
  }
}
