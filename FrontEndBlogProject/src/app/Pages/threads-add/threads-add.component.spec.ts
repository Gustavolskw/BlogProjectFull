import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ThreadsAddComponent } from './threads-add.component';

describe('ThreadsAddComponent', () => {
  let component: ThreadsAddComponent;
  let fixture: ComponentFixture<ThreadsAddComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ThreadsAddComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ThreadsAddComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
