<?php

namespace LaraZeus\Bolt\Http\Livewire\Admin;

use Filament\Forms;
use LaraZeus\Bolt\Models\Collection;
use LaraZeus\Bolt\Models\Field;
use LaraZeus\Bolt\Models\Form;
use Livewire\Component;

class Fields extends Component implements Forms\Contracts\HasForms
{
    use UsesBlankData;
    use Forms\Concerns\InteractsWithForms;

    public $title;
    public $content;

    protected function getForms() : array
    {
        return [
            'postForm' => $this->makeForm()->schema($this->getFormSchema()),
        ];
    }

    protected function getFormSchema() : array
    {
        return [
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\MarkdownEditor::make('content'),
        ];
    }

    public $fields;
    public $sec;
    public $fieldsModals = [];
    public $fieldsModalsItems = [ 'settings' => false ];
    public $formId;
    public $allCollection;
    protected $listeners = [ 'addField', 'collectionSaved', 'sectionSaved' => 'store' ];

    public function addCollection($collectionId)
    {
        $this->emit('addCollection', $collectionId);
    }

    protected $validationAttributes
        = [
            'fields.*.*.type' => 'field type',
            'fields.*.*.name' => 'field name',
        ];

    public function collectionSaved($collectionID, $fld)
    {
        $this->allCollection                                     = Collection::orderBy('id', 'desc')->get();
        $this->fields[$this->sec][$fld]['options']['dataSource'] = $collectionID;
    }

    public function mount($formId, $sec)
    {
        $this->allCollection = Collection::orderBy('id', 'desc')->get();
        $this->formId        = $formId;
        $this->sec           = $sec;

        if ($formId === null) {
            $this->fields[$this->sec][]       = $this->fieldData($this->sec);
            $this->fieldsModals[$this->sec][] = $this->fieldsModalsItems; // in edit? todo
        } else {
            $this->fields[$this->sec]       = Form::find($formId)->fields->toArray();
            $this->fieldsModals[$this->sec] = array_fill(0, count($this->fields[$this->sec]), $this->fieldsModalsItems); // in edit? todo
        }
    }

    public function rules()
    {
        return [
            'fields.*.*.name'        => 'required',
            'fields.*.*.description' => 'sometimes',
            'fields.*.*.ordering'    => 'sometimes',
            'fields.*.*.section_id'  => 'required',
            'fields.*.*.type'        => 'required',

            'fields.*.*.rules'   => 'sometimes',
            'fields.*.*.options' => 'sometimes',
        ];
    }

    public function addField($index)
    {
        $this->fields[$index][]           = $this->fieldData($index);
        $this->fieldsModals[$this->sec][] = $this->fieldsModalsItems;
    }

    public function openFieldModals($index, $type)
    {
        $this->fieldsModals[$this->sec][$index][$type] = true;
    }

    public function removeField($index)
    {
        unset($this->fields[$this->sec][$index]);
        unset($this->fieldsModals[$this->sec][$index]);
    }

    public function store($form, $section)
    {
        $this->validate();
        foreach ($this->fields as $sec => $fields) {
            foreach ($fields as $field) {
                $setField                  = Field::firstOrNew([ 'html_id' => $field['html_id'] ]);
                $setField->form_id         = $form;
                $setField->section_id      = $section;
                $setField->name            = $field['name'];
                $setField->description     = $field['description'] ?? null;
                $setField->type            = $field['type'];
                $setField->options         = $field['options'];
                $setField->rules           = $field['rules'];
                $setField->layout_position = $field['layout_position'] ?? 1;
                $setField->ordering        = $field['ordering'];

                $setField->save();
            }
        }

        $this->notify('your form has been saved!');

        return redirect()->route('bolt.admin.form.edit', [ 'formId' => $form ]);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('zeus-bolt::forms.create-field');
    }
}
