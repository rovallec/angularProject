import { Component, OnInit, ViewChild } from '@angular/core';
import { CKEditorComponent } from 'ng2-ckeditor';


@Component({
  selector: 'app-maquetacion',
  templateUrl: './maquetacion.component.html',
  styleUrls: ['./maquetacion.component.css']
})
export class MaquetacionComponent implements OnInit {

  ckeditorContent: string = "<b> Probando contenido</b>";
  @ViewChild(CKEditorComponent) ckEditor: CKEditorComponent;
  
  ngAfterViewChecked() {
    let editor = this.ckEditor.instance;
    editor.config.height = '400';
    editor.config.toolbarGroups = [
      { name: 'document', groups: ['mode', 'document', 'doctools'] },
      { name: 'clipboard', groups: ['clipboard', 'undo'] },
      { name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing'] },
      { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph'] },
      { name: 'insert', groups: ['insert'] }
    ]

    editor.config.removeButtons = 'Source, Save, Templates, Find, Replace, Scayt, SelectAll, Form, Radio';
  }
  
  constructor() { }

  ngOnInit() {
  }

    
}
