SELECT
    *,
    (upvotes_count + downvotes_count) as total,
    (upvotes_count/(upvotes_count+downvotes_count)) as rating
FROM (
    SELECT
        *,
        (SELECT COUNT(*) FROM votes AS uv WHERE uv.post_id = p.id AND uv.vote_type = "upvote") AS upvotes_count,
        (SELECT COUNT(*) FROM votes AS dv WHERE dv.post_id = p.id AND dv.vote_type = "downvote") AS downvotes_count
    FROM posts as p
    )
as ratings
