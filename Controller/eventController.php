<?php

namespace K2\Calendar\Controller;

use K2\Kernel\App;
use K2\Kernel\JsonResponse;
use K2\Calendar\Model\Event;
use K2\Kernel\Controller\Controller;

class eventController extends Controller
{

    protected function beforeFilter()
    {
        if (!$this->getRequest()->isAjax()) {
            return new JsonResponse(array(
                'message' => "Este controlador solo acepta peticiones Ajax"
                    ), 404);
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
            if (!$event = Event::findById($idEvent, false)) {
                return new JsonResponse(array(
                    'message' => "No existe el Evento con id = $idEvent"
                        ), 404);
            }
        } else {
            $event = new Event();
        }

        $this->event = $event;
    }

    public function save_action()
    {
        if ($this->getRequest()->isMethod('post')) {

            $data = (array) $this->getRequest()->post('event');

            if (isset($data['id']) && is_numeric($data['id'])) {
                if (!$event = Event::findById($data['id'], false)) {
                    return new JsonResponse(array(
                        'message' => "No existe el Evento con id = {$data['id']}"
                            ), 404);
                }
                unset($data['id']);
            } else {
                $event = new Event();
            }
            
            App::get('mapper')->bindPublic($event, $data);
            
            if ($event->save()) {
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

        if (Event::deleteByID($id)) {
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