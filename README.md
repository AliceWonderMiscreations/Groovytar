Groovytar
=========

A pipe dream privacy centric replacement for Gravatar.

I am what some people would call anti-social. I am not sure what that term
actually means, it seems to have different meanings depending upon the context,
but generally I do not socialize. Except online.

I see tremendous value in socializing online, but I also see tremendous danger
in the form of tracking.

There is value to a user having their identity "known" across different social
social media platforms, but there is also value in that user deciding when it
is obvious who they are and when it is not obvious who they are.

Just as an example, many people come from families where they would literally
be cut-off if other family members knew they were gay, or knew they did not
identify with the gender they were assigned at birth, etc.

For those people, the online community can be a godsend as it can allow them to
express who they are and interact with people who accept them for who they are,
but they need to be able to do so privately without the constant fear that they
will be outed to family and friends who may be abusive towards them as a
result.

The purpose of this project is to produce an open source replacement for the
[Gravatar](http://en.gravatar.com/) system of globally recognized avatars that
does a few things better.

Conceptually, I actually like Gravatar. I do. I am particularly fond of their
‘MonsterID’ avatars, those are really very well done and to me, they add some
fun to commenting on blogs.

However there are two major flaws with Gravatar from a privacy perspective.

1. The Gravatar is a simple `md5()` hash of the e-mail address. So for example,
   if your e-mail address happened to be `bufonidaelover@gmail.com` then the
   `md5()` hash is `5858a087b3f40b3876e804d5640222da`. Anyone can write a bot
   to search the web for blogs where that hash is associated with the avatar on
   the post comment and know that you almost certainly made that comment. The
   government almost certainly already does this.
2. The Gravatar domain uses tracking cookies. I do not know what they are doing
   with the data they collect, but it makes me feel very uncomfortable.
