Groovytar
=========

A pipe dream privacy centric replacement for Gravatar.

Live Demo: [https://trippyid.com/](https://trippyid.com/)

* [The Plan](#the-plan)
* [PictoGlyph](#pictoglyph)
* [Lots To Do](#lots-to-do)

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

The Plan
--------

John Doe uses the e-mail address `johndoe345@example.org` to post at BlogA,
BlogB, and BlogC. Instead of using Gravatar where all three of those blogs
would use `ca84c27b3856cdaa92bcf90fcab687df` to fetch an Avatar for John Doe,
those blogs use Groovytar and the each have their own set of salts they use
to come up with unique hashes that while generated from his e-mail address,
they are all different and there simply is no way for anyone to know the hashes
are related to each other.

To a bot scanning the web, they look like three different users at the three
different blogs.

Those blogs can opt to have an API key with Groovytar that can allow John Doe
to link the avatars used to his own image, if he so chooses. My Antifa friends
who have WordPress blogs may not even want to have the API key simply because
they don't want *any* of the people commenting on their blogs to be easily
identified to an outsider as a real person, but my Animal Rescue friends who
have blogs might decide to give their users that option.

When a blog has an API key, they can have a check box when submitting a comment
that allows the person commenting to *choose* to have his avatar used at that
blog linked to his or her real avatar.

The blog will take the user's e-mail address, hash it five times with sha512
and hash that result with ripemd160. That final hash will be sent as a unique
identifier along with the salted hash in the comments and the blog's API key.

If John Doe does not have an account with Groovytar, nothing changes. Nothing
changes because we have not verified that he actually wants any identity
linking taking place, he hasn't finished opting in yet.

However if John Does does have an account with Groovytar (or creates one) then
an e-mail will be sent to him telling him which Blogs want to link to his
custom photo. He has to log in to Groovytar to specifically approve it.

Once he has specifically approved it, then what happens is the Groovytar sends
an image of John Doe with requests for the salted hash specific to that site -
if one exists - or generic hash associated with John Doe if one does not exist.

I may get fancy and also allow John Doe to specify a Mastodon instance account
that the blog can link to with his name, haven't decided yet if I will do that.

So, John Doe will still have three different hashes used at blogs A, B, C but
they may all fetch the same image if he has linked them together, allowing
people to recognize him.

If in the future, John needs to unlink any of those sites from his real image,
he can. For example, if Blog C is a Animal Right blog and John wants to apply
for a job working at a sporting good store, John can choose to unlink Blog C
and it will no longer serve his image along with the comments he has left at
that blog.

That's the plan.


PictoGlyph
----------

This will be the default generated avatar when another is not specified. It
will provide a 4 x 4 grid of pictographs. There will be thirty-two different
pictographs when finished, currently nine have been created.

With 32 pictographs to choose from on a 4 x 4 grid, that makes a total of
32^16 different possible unique combinations - which numerically is:

    1,208,925,820,000,000,000,000,000

That's approximately the same number of stars as there are estimated to be in
the known universe.

Additionally there will be 128 different high contrast color combinations.
However I don't count them in the entropy for possible combinations because
they may not provide the same level of visual distinguishability for all
people.

The basic nutshell idea is that when looking at a post, the color combination
in concert with the presented glyphs will be enough to act as a sort of digital
signature we can visually identify as associated with a particular person.

Many identicon schemes exist. Most just translate a hash string into simple
geometic shapes, for example:

  [Gravitar Identicon](https://secure.gravatar.com/avatar/avatar/83c39f6bdf2d70a24f54949057370090?s=256&d=identicon)

I believe that creates confusion in the human mind rather than identity.
It might as well just be an image of the hash itself.

By using a grid of pictographs that represent human ideas, I believe it
triggers familiarity within the human brain making it easier to identify the
avatar with a person, the same way a picture of the person would - or their
signature on a piece of paper.

Currently the following pictographs exist:

* __Triquetra Knot__  
  Irish / Keltic Origin. Symbolizes earth, air, and water or life, death, and
  rebirth. Also symbolizes the Triple Goddess - Maiden, Mother, Crone.
  
* __Fertility Rune__  
  Celtic Fertility Rune.

* __Yin and Yang__  
  Opposite and often seemingly contrary forces are often actually complimentary
  to each other.

* __Asase Ye Duru__  
  West African symbol for the divinity of Mother Earth.

* __Elvin Star__  
  Also called a Faerie Star. Basically an equilateral seven pointed star. It is
  found in many different historic and some modern cultures, Seven seems to be
  significant number to a lot of people groups.

* __Hawaiian Turtle__  
  Symbolizes good luck and the importance of ocean life.

* __Boa Me Na Me Mmoa Wo__  
  West African (Adinkra), literally means "Help me and let me help you" - it
  symbolizes cooperation and interdependence, which is at the very core of how
  I personally define socialism.

* __Taino Coqui Frog__  
  I have been able to find what this glyph meant to the Taino people, but it is
  very common in their historic art. It clearly meant something to them. And it
  is a very beautiful reminder to me of the importance of ecological diversity.
  The Coqui frog is not yet extinct, but it is endangered like so many frogs.
  Hopefully it can continue on its evolutionary path.

* __Native North American Indian Sun Glyph__  
  I am not sure what tribe or tribes used this glyph, but it is a simplistic
  yet very beautiful depiction of the Sun, from which all life draws energy.

* __Noongar Waugal__  
  In Noongar Dreamtime Mythology, it was a Waugal that created the major rivers
  in their part of Australia.

* __Neo-Druid Awen__  
  Awen is a Celtic symbol showing three rays. The Neo-Druid version of this
  symbol often has three circles on the outside containing three dots and three
  rays, and is frequently used to represent masculine energy, feminine energy,
  and the balance between them.

* __Zuni Bear Fetish__  
  The Bear fetish is the Guardian of the West and has the power to heal and
  transform human passions into true wisdom. Bear reminds us that one of the
  great powers we have is the power of turning to solitude and introspection
  through which we integrate new experience and change.

There will eventually be 32 glyphs.

### Small Size Variant

A four by four grid of pictoglyphs really needs at least 120x120 to display
well. Not yet implemented, but planned is a variant than is three by three
instead of four by four that will work better for social media sites that
only want really small avatars.

That only will provide 36^9 (just over 100,000,000,000,000) different unique
combinations, but it still makes collisions extremely unlikely.


Lots To Do
----------

The Confeti identicon generator is working.

The Pictoglyph identicon generator has some of the planned glyphs but still
needs more glyphs created as well as more known good color combinations added.

I need a few identicons too, and I will need funding for hosting this project.

In addition to the Confeti generator, I want one that generates stylish
amphibians based upon the hash. Frogs, Toads, Newts, Salamanders, maybe even
Caecilians.

For websites migrating from Gravatar, these would be used in place of the
Wavatar generated avatars.

I also would like a replacement for the MonsterID identicons.

It also might be fun to have one that generates Gingerbread Cookie identicons.

And some others.


----------------------------------------
__EOF__