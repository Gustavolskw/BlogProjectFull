import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ThreadsFormComponent } from './threads-form.component';

describe('ThreadsFormComponent', () => {
  let component: ThreadsFormComponent;
  let fixture: ComponentFixture<ThreadsFormComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ThreadsFormComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ThreadsFormComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
