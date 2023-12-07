from collections import Counter
from functools import cmp_to_key
from aoc import read_input

lines = read_input()

scores = {
    "A": 13,
    "K": 12,
    "Q": 11,
    "J": 10,
    "T": 9,
    "9": 8,
    "8": 7,
    "7": 6,
    "6": 5,
    "5": 4,
    "4": 3,
    "3": 2,
    "2": 1,
}

part = 1


def get_type(h):
    c = Counter(h)

    if part == 2 and "J" in h:
        j_count = c["J"]
        del c["J"]
        if len(c) == 0:
            c["A"] = 5
        else:
            mc, _ = c.most_common()[0]
            c[mc] += j_count

    if len(c) == 1:
        return 7
    if len(c) == 2:
        if 4 in c.values():
            return 6
        if 3 in c.values():
            return 5
    if 3 in c.values():
        return 4

    if 2 in c.values():
        num_pairs = 0
        for v in c.values():
            if v == 2:
                num_pairs += 1
        if num_pairs == 2:
            return 3
        else:
            return 2

    return 1


def cmp(hb1, hb2):
    h1 = hb1[0]
    h2 = hb2[0]

    t1 = get_type(h1)
    t2 = get_type(h2)

    if t1 > t2:
        return 1
    elif t2 > t1:
        return -1

    for c1, c2 in zip(h1, h2):
        if scores[c1] > scores[c2]:
            return 1
        elif scores[c2] > scores[c1]:
            return -1

    return 0


hand_bids = []

for hand in lines:
    hand, b = hand.split()
    hand_bids.append((hand, int(b)))


def get_winnings(hand_bids):
    in_order = sorted(hand_bids, key=cmp_to_key(cmp))
    total = 0
    rank = 1
    for _, bid in in_order:
        total += bid * rank
        rank += 1
    return total


print(get_winnings(hand_bids))

scores["J"] = 0
part = 2

print(get_winnings(hand_bids))

