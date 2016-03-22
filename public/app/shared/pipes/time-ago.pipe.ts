import {Pipe, PipeTransform} from 'angular2/core';
/*
 * Raise the value exponentially
 * Takes an exponent argument that defaults to 1.
 * Usage:
 *   value | exponentialStrength:exponent
 * Example:
 *   {{ 2 |  exponentialStrength:10}}
 *   formats to: 1024
*/
@Pipe({name: 'timeAgo'})
export class TimeAgoPipe implements PipeTransform {
  transform(value:string, args:string[]) : any {

    // set locale
    if(args[0]) {
      moment.locale(args[0]);
    }
    else {
      moment.locale('en');
    }

    return moment(value).fromNow();
    //return 'placeholder';

  }
}