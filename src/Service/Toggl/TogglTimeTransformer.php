<?php

namespace App\Service\Toggl;

use App\Form\TimeEntryType;
use App\Model\TogglTimeEntry;
use Symfony\Component\Form\FormFactoryInterface;

class TogglTimeTransformer
{
    /** @var FormFactoryInterface */
    protected $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function transform(array $entry): TogglTimeEntry
    {
        $form = $this->formFactory->create(TimeEntryType::class);
        $form->submit($entry);

        return $form->getData();
    }
}
