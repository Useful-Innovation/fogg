<?= $php; ?> namespace <?= $namespace; ?>;

use App\Api\System\AdminController;
use App\Api\Models\<?= $model_name; ?>;

class <?= $class; ?> extends AdminController
{
  public function index($request) {
    return $this->response(['<?= $plural; ?>' => <?= $model_name; ?>::all()]);
  }

  public function create($request) {
    return $this->response(['<?= $singular; ?>' => new <?= $model_name; ?>()]);
  }

  public function store($request) {
    $<?= $singular; ?> = new <?= $model_name; ?>();
    $<?= $singular; ?>->updateAttributes($request->post('<?= $singular; ?>'));
    $this->redirect('<?= $plural; ?>.edit', $<?= $singular; ?>->id);
  }

  public function edit($request) {
    $<?= $singular; ?> = <?= $model_name; ?>::find($request->get('fogg-id'));
    return $this->response(['<?= $singular; ?>' => $<?= $singular; ?>]);
  }

  public function update($request) {
    $<?= $singular; ?> = <?= $model_name; ?>::find($request->get('fogg-id'));
    $<?= $singular; ?>->updateAttributes($request->post('<?= $singular; ?>'));
    $this->redirect('<?= $plural; ?>.edit', $<?= $singular; ?>->id);
  }

  public function delete($request) {
    $<?= $singular; ?> = <?= $model_name; ?>::find($request->get('fogg-id'));
    $<?= $singular; ?>->delete();
    $this->redirect('<?= $plural; ?>.index');
  }
}
