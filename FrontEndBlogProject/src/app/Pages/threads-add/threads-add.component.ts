import { Component } from '@angular/core';
import { ThreadsFormComponent } from "../../Shared/threads-form/threads-form.component";

@Component({
  selector: 'app-threads-add',
  standalone: true,
  imports: [ThreadsFormComponent],
  templateUrl: './threads-add.component.html',
  styleUrl: './threads-add.component.scss'
})
export class ThreadsAddComponent {

}
