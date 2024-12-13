import {Component, EventEmitter, OnInit, Output} from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import {Router, RouterLink} from '@angular/router';
import { AuthService } from '../../Services/auth.service';
import Swal from 'sweetalert2';
import {NgIf} from '@angular/common';
import {TokenService} from "../../Services/token.service";


@Component({
  selector: 'app-login',
  standalone: true,
    imports: [
        ReactiveFormsModule,
        NgIf,
        RouterLink,
    ],
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss'] // Corrigido de styleUrl para styleUrls
})
export class LoginComponent implements OnInit{
  loginForm!: FormGroup;
  @Output() loginSucesso = new EventEmitter();
  @Output() isLoginPage = new EventEmitter(true);

  constructor(
    private formBuilder: FormBuilder,
    private router: Router,
    private authService: AuthService,
    private tokenService:TokenService
  ) {}

  ngOnInit(): void {
    this.loginForm = this.formBuilder.group({
      email: [null, [Validators.required, Validators.email]],
      senha: [null, [Validators.required, Validators.minLength(3)]]
    });
  }

  onLogin(): void {
    if (!this.loginForm.valid) {
      this.loginForm.markAllAsTouched();
      return;
    } else {
      const { email, senha } = this.loginForm.value;
      this.authService.login(email, senha).subscribe({
        next: (resp) => {
          this.tokenService.salvarToken(resp.body?.token != null ? resp.body?.token : "")
          let timerInterval:any;
          Swal.fire({
            icon: 'success',
            title: resp.body?.message,
            html: "redirecionando em <b></b>.",
            timer: 2000,
            timerProgressBar: true,
            didOpen: () => {
              Swal.showLoading();
              const timer = Swal.getPopup()?.querySelector("b");
              timerInterval = setInterval(() => {
                if(timer){
                  timer.textContent = `${Swal.getTimerLeft()}`;
                }
              }, 100);
            },
            willClose: () => {
              clearInterval(timerInterval);
            }
          }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
              this.loginSucesso.emit(resp);
              this.router.navigate(['/home']).then();
            }
          });
        },
        error: (err) => {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: err.error.message,
          });
          this.loginForm.get('senha')?.reset()
          return;
        }
      });
    }
  }

}
