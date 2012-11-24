<?php

namespace K2\Calendar\Controller;

use KumbiaPHP\Form\Form;
use K2\Calendar\Model\Event;
use KumbiaPHP\Kernel\JsonResponse;
use KumbiaPHP\Kernel\Controller\Controller;

class EventController extends Controller
{

    protected function beforeFilter()
    {
        $this->setTemplate(null);
        if (!$this->getRequest()->isAjax()) {
            return $this->renderNotFound("Este controlador solo acepta peticiones Ajax");
        }
    }

    public function form($idEvent = null)
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

    public function save($id = null)
    {
        if (null !== $id) {
            if (!$event = Event::findByPK((int) $id)) {
                return new JsonResponse(array(
                            'message' => "No existe el Evento con id = $id"
                                ), 404);
            }
        } else {
            $event = new Event();
        }

        if ($this->getRequest()->isMethod('post')) {


            $data = $this->getRequest()->get('event');

            if (isset($data['id'])) {
                unset($data['id']);
            }

            if ($event->save($data)) {
                return new JsonResponse((array) $event);
            } else {
                return new JsonResponse(array(
                            'message' => 'No se pudo Guardar El Evento',
                            'errros' => $event->getErrors(),
                                ), 500);
            }
        }
        return new JsonResponse(array(
                    'message' => "No se ha enviado el formulario"
                        ), 404);
    }

}