import { Injectable } from '@angular/core';
import Swal from 'sweetalert2';
import {NgFor} from "@angular/common";
@Injectable({
  providedIn: 'root'
})
export class SweetAlertService {

  constructor() { }


  toastSuccess(message:string){
    const Toast = Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      }
    });
    Toast.fire({
      icon: "success",
      title: message
    });
  }

  toastError(message:string){
    const Toast = Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      }
    });
    Toast.fire({
      icon: "error",
      title: message
    });
  }


  errorAlert(errorMessage:string ) {
    Swal.fire({
      icon: "error",
      title: "Algo de errado aconteceu!",
      text: errorMessage,
    });
  }


  erroDeValidacaoAlerta(errorMessage:string, errorBag:Array<string>){

    Swal.fire({
      icon: "error",
      title: "Algo de errado aconteceu!",
      html: `
        @for(error of errorBag; track errrbag.error){
      <p class="text-danger">{{error}}</p>
      }
      `,
    });

  }



}
