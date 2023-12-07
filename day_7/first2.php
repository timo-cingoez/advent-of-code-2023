<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

$input = "32T3K 765
T55J5 684
KK677 28
KTJJT 220
QQQJA 483";

enum Card: string
{
    case Ace ='A';
    case King = 'K';
    case Queen = 'Q';
    case Jack = 'J';
    case Ten = 'T';
    case Nine = '9';
    case Eight = '8';
    case Seven = '7';
    case Six = '6';
    case Five = '5';
    case Four = '4';
    case Three = '3';
    case Two = '2';

    public function strength(bool $joker): int
    {
        if ($joker) {
            return match ($this) {
                Card::Ace => 13,
                Card::King => 12,
                Card::Queen => 11,
                Card::Ten => 10,
                Card::Nine => 9,
                Card::Eight => 8,
                Card::Seven => 7,
                Card::Six => 6,
                Card::Five => 5,
                Card::Four => 4,
                Card::Three => 3,
                Card::Two => 2,
                Card::Jack => 1,
            };
        }

        return match ($this) {
            Card::Ace => 13,
            Card::King => 12,
            Card::Queen => 11,
            Card::Jack => 10,
            Card::Ten => 9,
            Card::Nine => 8,
            Card::Eight => 7,
            Card::Seven => 6,
            Card::Six => 5,
            Card::Five => 4,
            Card::Four => 3,
            Card::Three => 2,
            Card::Two => 1,
        };
    }

    public function compare(Card $other, bool $joker): int
    {
        return $this->strength($joker) <=> $other->strength($joker);
    }
}

enum Type: int
{
    case FiveOfAKind = 7;
    case FourOfAKind = 6;
    case FullHouse = 5;
    case ThreeOfAKind = 4;
    case TwoPair = 3;
    case OnePair = 2;
    case HighCard = 1;

    public function strength(): int
    {
        return $this->value;
    }

    public function withJokers(int $jokers): Type
    {
        return match ($this) {
            Type::FiveOfAKind, Type::FourOfAKind, Type::FullHouse => Type::FiveOfAKind,
            Type::ThreeOfAKind => Type::FourOfAKind,
            Type::TwoPair => $jokers === 2 ? Type::FourOfAKind : Type::FullHouse,
            Type::OnePair => Type::ThreeOfAKind,
            Type::HighCard => Type::OnePair,
        };
    }
}
final readonly class Hand
{
    private Collection $groups;

    public function __construct(
        private Collection $cards,
        private int $bid,
        private bool $joker,
    ) {
        $this->groups = $this->cards->countBy('value')->sortDesc();
    }

    public function cards(): Collection
    {
        return $this->cards;
    }

    public function type(): Type
    {
        $type = match ($this->groups->values()->all()) {
            [5] => Type::FiveOfAKind,
            [4, 1] => Type::FourOfAKind,
            [3, 2] => Type::FullHouse,
            [3, 1, 1] => Type::ThreeOfAKind,
            [2, 2, 1] => Type::TwoPair,
            [2, 1, 1, 1] => Type::OnePair,
            [1, 1, 1, 1, 1] => Type::HighCard,
            default => throw new LogicException(),
        };

        $jokers = $this->groups->get(Card::Jack->value, 0);

        return $this->joker && $jokers > 0 ? $type->withJokers($jokers) : $type;
    }

    public function bid(): int
    {
        return $this->bid;
    }

    public function compare(Hand $other): int
    {
        $compared = $this->type()->strength() <=> $other->type()->strength();

        if ($compared === 0) {
            return $this->cards()
                ->zip($other->cards())
                ->map(fn (Collection $cards): int => $cards->first()->compare($cards->last(), $this->joker))
                ->first(static fn (int $result): bool => $result !== 0, 0);
        }

        return $compared;
    }

    public static function fromString(string $notation, bool $joker): self
    {
        [$cards, $bid] = explode(' ', $notation);

        return new self(
            cards: Collection::make(str_split($cards))->map(Card::from(...)),
            bid: intval($bid),
            joker: $joker,
        );
    }
}

$part1 = Str::of($input)
    ->split("/\n/")
    ->map(static fn (string $notation) => Hand::fromString($notation, false))
    ->sort(static fn (Hand $a, Hand $b): int => $a->compare($b))
    ->values()
    ->map(static fn (Hand $hand, int $rank): int => $hand->bid() * ($rank + 1))
    ->sum();

$part2 = Str::of($input)
    ->split("/\n/")
    ->map(static fn (string $notation) => Hand::fromString($notation, true))
    ->sort(static fn (Hand $a, Hand $b): int => $a->compare($b))
    ->values()
    ->map(static fn (Hand $hand, int $rank): int => $hand->bid() * ($rank + 1))
    ->sum();

$result = [$part1, $part2];
