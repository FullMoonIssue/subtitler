# Subtitler

This project aims to manage the subtitles time translations.

## Domain

### Block

A block is formed with :

- a number
- a begin time
- an ending time
- one or more lines of text

A time contains :

- an hour
- a minute
- a second
- a millisecond

The block textual representation will looks like this :

```
17
00:01:53,660 --> 00:01:55,360
This is a sentence.
Now, an other sentence.
```

### Matrix

A matrix is just a sum of blocks combined together separate by an empty line (that represent the content of a .srt file)

## Install

```
composer install
```

## Commands

_Your .srt file have to be present in the Command/input folder_

* Find a translate id by searching a text or a time through the .srt file

```
php console.php subtitler:search mySrtFile.srt --by-text=internet
or
php console.php subtitler:search mySrtFile.srt --by-time=00:46:40,740
```

* Do a time translation

```
* Add two seconds for all blocks
php console.php subtitler:translate-time mySrtFile.srt --translate=+2s

* Subtract three minutes from the block 15
php console.php subtitler:translate-time mySrtFile.srt --translate=-3m --from=15

* Add four hours from the block 45 to the block 100
php console.php subtitler:translate-time mySrtFile.srt --translate=+4h --from=45 --to=100
```

## Tests

```
make test
```