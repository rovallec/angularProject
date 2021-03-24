import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ImportWavesComponent } from './import-waves.component';

describe('ImportWavesComponent', () => {
  let component: ImportWavesComponent;
  let fixture: ComponentFixture<ImportWavesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ImportWavesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ImportWavesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
