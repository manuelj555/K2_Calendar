<?php

namespace K2\Calendar\Controller;

use KumbiaPHP\Form\Form;
use K2\Calendar\Model\Event;
use KumbiaPHP\Kernel\JsonResponse;
use KumbiaPHP\Kernel\Controller\Controller;

class eventController extends Controller
{

    protected function beforeFilter()
    {
        $this->setTemplate(null);
        if (!$this->getRequest()->isAjax()) {
            return $this->renderNotFound("Este controlador solo acepta peticiones Ajax");
        }
    }

    public function index_action()
    {
        $start = $this->getRequest()->get('start');
        $end = $this->getRequest()->get('end');

        $query = Event::createQuery();

        $query->where('date(start) >= date(:start) AND date(end) <= date(:end)')
                ->bind(array(
                    'start' => Event::dateFormat($start),
                    'end' => Event::dateFormat($end),
                ));
        return new JsonResponse(Event::findAll('array'));
    }

    public function form_action($idEvent = null)
    {
        if (null !== $idEvent) {
            if (!$event = Event::findByPK((int) $idEvent)) {
                return new JsonResponse(array(
                            'message' => "No existe el Evento con id = $idEvent"
                                ), 404);
            }
        } else {
            $event = new Event();
        }

        $form = new Form($event);

        $form->add('id', 'hidden');
        $form->add('title')->setLabel('Título');
        $form->add('description', 'textarea')->setLabel('Descripción');
        $form->add('start', 'hidden');
        $form->add('end', 'hidden');
        $form->add('color', 'color')
                ->setLabel('Color del Evento');

        if (null === $form['color']->getValue()) {
            $form['color'] = '#3266cc';
        }

        $this->form = $form;
    }

    public function save_action()
    {
        if ($this->getRequest()->isMethod('post')) {

            $data = (array) $this->getRequest()->get('event');

            if (isset($data['id']) && null != $data['id']) {
                if (!$event = Event::findByPK((int) $data['id'])) {
                    return new JsonResponse(array(
                                'message' => "No existe el Evento con id = {$data['id']}"
                                    ), 404);
                }
            } else {
                $event = new Event();
            }

            if ($event->save($data)) {
                return new JsonResponse(get_object_vars($event));
            } else {
                return new JsonResponse(array(
                            'message' => 'No se pudo Guardar El Evento',
                            'errors' => $event->getErrors(),
                                ), 500);
            }
        }
        return new JsonResponse(array(
                    'message' => "No se ha enviado el formulario"
                        ), 404);
    }

    public function remove_action($id)
    {
        $event = new Event();

        if ($event->deleteByPK($id)) {
            return new JsonResponse(array(
                        'message' => "El Evento fué eliminado"
                    ));
        } else {
            return new JsonResponse(array(
                        'message' => 'No se pudo Eliminar El Evento',
                        'errors' => $event->getErrors(),
                            ), 500);
        }
    }

}